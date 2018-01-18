//*********GPS to save GPS to MySQL Server/

#include <Arduino.h>
#include <WiFiConnector.h>
#include <ESP8266WebServer.h>
#include <SoftwareSerial.h>
#include <TinyGPS++.h>
#include <Wire.h>
#include <SPI.h>
#include <ESP8266HTTPClient.h>
#include <Adafruit_Sensor.h>

static const int RXPin = 12, TXPin = 13;
static const uint32_t GPSBaud = 9600;
// The TinyGPS++ object
TinyGPSPlus gps;
// The serial connection to the GPS module
SoftwareSerial ss(RXPin, TXPin);
// Replace with your network details
const char* ssid = "OMDUVISSTE";
const char* password = "grodanboll";
float h, t, p, pin, dp, a;
float hmax, hmin = 100, pmax, pmin = 2000, tmax, tmin = 100;
int counter = 0;
int ok = 0;
// Web Server on port 80
WiFiServer server(80);

void GETtoMysql(){
  HTTPClient http;

        float temp = t;
        float humidity = h;
        float altitude = a;
        float pressure = p;
        float tempmin = tmin;
        float tempmax = tmax;
        float humiditymin = hmin;
        float humiditymax = hmax;
        float pressuremin = pmin;
        float pressuremax = pmax;
        String url = "http://192.168.3.218/addGPSall.php?temp="+String(temp);
        url = url + ":" + String(humidity);
        url = url + ":" + String(altitude);
        url = url + ":" + String(pressure);
        url = url + ":" + String(tempmin);
        url = url + ":" + String(tempmax);
        url = url + ":" + String(humiditymin);
        url = url + ":" + String(humiditymax);
        url = url + ":" + String(pressuremin);
        url = url + ":" + String(pressuremax);
        Serial.println(url);
        http.begin(url);

        //GET method
        int httpCode = http.GET();
        if(httpCode > 0)
        {
          Serial.printf("[HTTP] GET...code: %d\n", httpCode);
          if(httpCode == HTTP_CODE_OK)
          {
              String payload = http.getString();
              Serial.println(payload);
          }
       }
       else
       {
            Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
       }
          http.end();
}
// only runs once on boot
void setup() {
  // Initializing serial port for debugging purposes
  Serial.begin(115200);
  ss.begin(GPSBaud);
  delay(10);
  Wire.setClock(100000);
  // Connecting to WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  // Starting the web server
  server.begin();
  Serial.println("Web server running. Waiting for the ESP IP...");
  delay(10000);

  // Printing the ESP IP address
  Serial.println(WiFi.localIP());
  Serial.println(F("BME280 test"));

}
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

static void smartDelay(unsigned long ms)
{
  unsigned long start = millis();
  do
  {
    while (ss.available())
      gps.encode(ss.read());
  } while (millis() - start < ms);
}

static void printFloat(float val, bool valid, int len, int prec)
{
  if (!valid)
  {
    while (len-- > 1)
      Serial.print('*');
    Serial.print(' ');
  }
  else
  {
    Serial.print(val, prec);
    int vi = abs((int)val);
    int flen = prec + (val < 0.0 ? 2 : 1); // . and -
    flen += vi >= 1000 ? 4 : vi >= 100 ? 3 : vi >= 10 ? 2 : 1;
    for (int i=flen; i<len; ++i)
      Serial.print(' ');
  }
  smartDelay(0);
}

static void printInt(unsigned long val, bool valid, int len)
{
  char sz[32] = "*****************";
  if (valid)
    sprintf(sz, "%ld", val);
  sz[len] = 0;
  for (int i=strlen(sz); i<len; ++i)
    sz[i] = ' ';
  if (len > 0)
    sz[len-1] = ' ';
  Serial.print(sz);
  smartDelay(0);
}

static void printDateTime(TinyGPSDate &d, TinyGPSTime &t)
{
  if (!d.isValid())
  {
    Serial.print(F("********** "));
  }
  else
  {
    char sz[32];
    sprintf(sz, "%02d/%02d/%02d ", d.month(), d.day(), d.year());
    Serial.print(sz);
  }

  if (!t.isValid())
  {
    Serial.print(F("******** "));
  }
  else
  {
    char sz[32];
    sprintf(sz, "%02d:%02d:%02d ", t.hour(), t.minute(), t.second());
    Serial.print(sz);
  }

  printInt(d.age(), d.isValid(), 5);
  smartDelay(0);
}

static void printStr(const char *str, int len)
{
  int slen = strlen(str);
  for (int i=0; i<len; ++i)
    Serial.print(i<slen ? str[i] : ' ');
  smartDelay(0);
}


//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// runs over and over again

void loop() {
  // Listenning for new clients
  WiFiClient client = server.available();
  //delay(10000);
  //ReadValues();

    // closing the client connection

    client.stop();
    Serial.println("Client disconnected.");
    ++counter;
      Serial.println(ok);
    if (counter > 1 or ok == 1){
  //    GETtoMysql();
      delay(2000);
  //    Serial.print(" I'm going to sleep!! 10 min");
  //    ESP.deepSleep(600000000);
    }
    static const double LONDON_LAT = 58.41086, LONDON_LON = 15.62157;

    printInt(gps.satellites.value(), gps.satellites.isValid(), 5);
    printInt(gps.hdop.value(), gps.hdop.isValid(), 5);
    printFloat(gps.location.lat(), gps.location.isValid(), 11, 6);
    printFloat(gps.location.lng(), gps.location.isValid(), 12, 6);
    printInt(gps.location.age(), gps.location.isValid(), 5);
    printDateTime(gps.date, gps.time);
    printFloat(gps.altitude.meters(), gps.altitude.isValid(), 7, 2);
    printFloat(gps.course.deg(), gps.course.isValid(), 7, 2);
    printFloat(gps.speed.kmph(), gps.speed.isValid(), 6, 2);
    printStr(gps.course.isValid() ? TinyGPSPlus::cardinal(gps.course.deg()) : "*** ", 6);

    unsigned long distanceKmToLondon =
      (unsigned long)TinyGPSPlus::distanceBetween(
        gps.location.lat(),
        gps.location.lng(),
        LONDON_LAT,
        LONDON_LON) / 1000;
    printInt(distanceKmToLondon, gps.location.isValid(), 9);

    double courseToLondon =
      TinyGPSPlus::courseTo(
        gps.location.lat(),
        gps.location.lng(),
        LONDON_LAT,
        LONDON_LON);

    printFloat(courseToLondon, gps.location.isValid(), 7, 2);

    const char *cardinalToLondon = TinyGPSPlus::cardinal(courseToLondon);

    printStr(gps.location.isValid() ? cardinalToLondon : "*** ", 6);

    printInt(gps.charsProcessed(), true, 6);
    printInt(gps.sentencesWithFix(), true, 10);
    printInt(gps.failedChecksum(), true, 9);
    Serial.println();

    smartDelay(1000);

    if (millis() > 5000 && gps.charsProcessed() < 10)
      Serial.println(F("No GPS data received: check wiring"));


}
