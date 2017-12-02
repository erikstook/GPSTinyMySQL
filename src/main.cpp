/*********
  Project: BME Weather Web server using NodeMCU
  Implements Adafruit's sensor libraries.
  Complete project is at: http://embedded-lab.com/blog/making-a-simple-weather-web-server-using-esp8266-and-bme280/

  Modified code from Rui Santos' Temperature Weather Server posted on http://randomnerdtutorials.com
*********/

#include <Arduino.h>
#include <WiFiConnector.h>
#include <ESP8266WebServer.h>
//#include <ArduinoOTA.h>
//#include <ESP8266mDNS.h>
#include <Wire.h>
#include <SPI.h>
#include <ESP8266HTTPClient.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#define SEALEVELPRESSURE_HPA (1013.25)
Adafruit_BME280 bme; // I2C

// Replace with your network details
const char* ssid = "OMDUVISSTE";
const char* password = "grodanboll";
float h, t, p, pin, dp, a;
float hmax, hmin = 100, pmax, pmin = 2000, tmax, tmin = 100;
int counter = 0;
int ok = 0;
/*char temperatureFString[6];
char temperaturemaxString[6];
char temperatureminString[6];
char dpString[6];
char humidityString[6];
char humiditymaxString[6];
char humidityminString[6];
char pressureString[7];
char pressuremaxString[7];
char pressureminString[7];
char pressureInchString[6];
char altitude[6];*/

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
        String url = "http://192.168.3.218/addweatherall.php?temp="+String(temp);
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
  delay(10);
  Wire.begin(D3, D4);
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

  if (!bme.begin(0x76)) {
    Serial.println("Could not find a valid BME280 sensor, check wiring!");
    while (1);
  }
}

void getWeather() {

    a = bme.readAltitude(SEALEVELPRESSURE_HPA);
    h = bme.readHumidity();
    if (h > hmax) hmax = h;
    if (h < hmin) hmin = h;
    t = bme.readTemperature();
    t = t*1.8+32.0;
    t = (t-32)/1.8000; // F to C
    dp = t-0.36*(100.0-h);
    if (t > tmax) tmax = t; // Check Max Temp
    if (t < tmin) tmin = t; // Check the Min Temp
    p = bme.readPressure()/100.0F;
    if (p > pmax) pmax = p;
    if (p < pmin) pmin = p;
    pin = 0.02953*p;
//    dtostrf(t, 5, 1, temperatureFString);
  //  dtostrf(h, 5, 1, humidityString);
//    dtostrf(hmax, 5, 1, humiditymaxString);
  //  dtostrf(hmin, 5, 1, humidityminString);
  //  dtostrf(p, 6, 1, pressureString);
  //  dtostrf(pmax, 6, 1, pressuremaxString);
  //  dtostrf(pmin, 6, 1, pressureminString);
  //  dtostrf(pin, 5, 2, pressureInchString);
  //  dtostrf(dp, 5, 1, dpString);
  //  dtostrf(a, 6, 1, altitude);
  //  dtostrf(tmin, 6, 1, temperatureminString);
  //  dtostrf(tmax, 6, 1, temperaturemaxString);
    delay(1000);

}

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
      getWeather();
      Serial.println(ok);
    if (counter > 1 or ok == 1){
      GETtoMysql();
      delay(2000);
      Serial.print(" I'm going to sleep!! 10 min");
      ESP.deepSleep(600000000);
    }
 getWeather();
 Serial.println("Temp:");
 Serial.println(t);
 Serial.println("Humidity:");
 Serial.println(h);
 Serial.println("Altitude:");
 Serial.println(a);
 Serial.println("Pressure:");
 Serial.println(p);


}
