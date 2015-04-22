#ifndef DATABASE_H
#define DATABASE_H
#include <QtDebug>
#include <QDateTime>
#include <QtSql/QtSql>

void log(QString text);
void log(QStringList text);
typedef QMap<QString, QString> StringMap;
class Database{
private:
    QSqlDatabase daba;
public:
    Database(){
        daba = QSqlDatabase::addDatabase("QMYSQL");
        daba.setHostName("localhost");
        daba.setDatabaseName("UIoT_Middleware");
        daba.setUserName("uiot_admin");
        daba.setPassword("admin321");
        log("Database setup finished.");
    }
    int getServiceId(QString udn, QString service_id);
    QString getServiceIdString(QString service_id);
    QStringList getActions(int service_id);
    QList<QStringList> getDevices();
    QStringList getServicesByDeviceId(int device_id);
    QStringList getDeviceByUdn(QString udn);
    QList<StringMap> getArgsByServiceIdAndActionName(int service_id,QString action_name);
    QList<StringMap> getInfo(QString zigbee_addr, QString pin_number);


    StringMap getStateVariable(int service_id,QString state_variable_name);
    QString getServiceXbeeAddr(int id);
};
extern Database db;
#endif // DATABASE_H
