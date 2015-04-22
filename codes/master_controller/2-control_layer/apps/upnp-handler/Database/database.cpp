#include <QtDebug>
#include <QDateTime>
#include <QtSql/QtSql>
#include "database.h"

Database db = Database();

QList<QStringList> Database::getDevices(){
    QList<QStringList> devices;
    //Open connection
    bool openned = daba.open();
    if ( openned ) {
        log("Database open to get Devices UDN.");
        QString q = QString("SELECT  `PK_Id`,`TE_UDN`,`TE_Device_Type`,`TE_XML_Link`,`TE_Friendly_Name`,`FK_Slave_Controller`  FROM Device WHERE `BO_Deleted` = 0 ");
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                QStringList info;
                info << query.value(0).toString();//id
                info << query.value(1).toString();//udn
                info << query.value(2).toString();//device_type
                info << query.value(3).toString();//description_xml
                info << query.value(4).toString();//Friendly name
                info << query.value(5).toString();//Slave Controller
                log(QString( "found id %1\t%2\n").arg(info[0]).arg(info[1]));
                devices << info;
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns device UDN
    return devices;
}

int Database::getServiceId(QString udn, QString service_id){
    //Open connection
    bool openned = daba.open();
    int  Id = 0;
    if ( openned ) {
        log("Database open to get Service Id - SID.");
        QString q = QString("SELECT * FROM Service WHERE FK_Device = (SELECT PK_Id FROM Device WHERE TE_UDN='%1' AND BO_Deleted =0 LIMIT 1) AND TE_Service_Id = '%2' AND BO_Deleted=0 LIMIT 1 ").arg(udn).arg(service_id);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                Id = query.value(0).toInt();
                QString Name = query.value(2).toString();
                log(QString( "Found service %1\t%2\n").arg(Id).arg( Name ));
                break;
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns service id
    return Id;
}

QString Database::getServiceIdString(QString service_id){
    //Open connection
    bool openned = daba.open();
    QString Name;
    if ( openned ) {
        log("Database open to get Service Id - SID.");
        QString q = QString("SELECT PK_Id, TE_Service_Id FROM Service WHERE PK_Id = '%1' AND BO_Deleted = 0 LIMIT 1 ").arg(service_id);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                int Id = query.value(0).toInt();
                Name = query.value(1).toString();
                log(QString( "Found service %1\t%2\n").arg(Id).arg( Name ));
                break;
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns service id
    return Name;
}

QString  Database::getServiceXbeeAddr(int id){
    //Open connection
    bool openned = daba.open();
    QString  addr;
    if ( openned ) {
        log("Database open to get Service Xbee_Addr.");
        QString q = QString("SELECT `TE_Address` FROM Slave_Controller WHERE `PK_Unic_Name` = (SELECT `FK_Slave_Controller` FROM Device WHERE PK_Id = (SELECT FK_Device FROM Service WHERE PK_Id='%1' AND BO_Deleted = 0 LIMIT 1) AND BO_Deleted = 0 LIMIT 1) AND BO_Deleted = 0 LIMIT 1 ").arg(id);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                addr = query.value(0).toString();
                log(QString( "Found Addr %1\n").arg(addr));
                break;
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns service id
    return addr;
}


QStringList Database::getServicesByDeviceId(int device_id){
    //Open connection
    bool openned = daba.open();
    QStringList  ids;
    if ( openned ) {
        log("Database open to get Service Id UPnP way.");
        QString q = QString("SELECT PK_Id,TE_Service_Id FROM Service WHERE FK_Device = '%1' AND BO_Deleted = 0").arg(device_id);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                QString Id = query.value(1).toString();
                QString Name = query.value(0).toString();
                log(QString( "Found service %1 for device %2\n").arg(Id).arg( Name ));
                ids << Id;
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns service id
    return ids;
}
QStringList Database::getDeviceByUdn(QString udn){
    QStringList info;
    //Open connection
    bool openned = daba.open();
    if ( openned ) {
        log("Database open to get Device by UDN.");
        QString q = QString("SELECT  `PK_Id`,`TE_Device_Type`,`TE_XML_Link` FROM Device WHERE `TE_UDN`='%1' AND `BO_Deleted` = 0 ").arg(udn);
        QSqlQuery query(q);
        if ( !query.isActive() )
                  log("Query Error" + query.lastError().text());
        else while (query.next()) {
                info << query.value(0).toString();//id
                info << query.value(1).toString();//device_type
                info << query.value(2).toString();//description_xml
                log(QString( "found id %1\t%2\n").arg(info[0]).arg(info[1]));
        }
        daba.close();
    }else
        log("Error opening database\n");
    //Returns device UDN
    return info;
}
QStringList Database::getActions(int service_id){
    //Open connection
    bool openned = daba.open();
    QStringList actions;
    if ( openned ) {
        //--- run a query To select services ---
        log("Database open to get Actions.");
        QString q = QString("select * from Action WHERE FK_Service = '%1' AND BO_Deleted=0").arg(service_id);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
          log("Query Error" + query.lastError().text());
        else while (query.next()) {
            QString Id = query.value(0).toString();
            QString Name = query.value(2).toString();
            actions << Name;
            log(QString( "Found action %1\t%2\n").arg(Id).arg( Name ));
        }
        daba.close();
    }
    else
        //--- case erros happen ---
        log("Error opening database\n");

    //Return actions
    return actions;
}

QList<StringMap> Database::getArgsByServiceIdAndActionName(int service_id,QString action_name){
    //Open connection
    bool openned = daba.open();
    QList<StringMap> arguments;
    if ( openned ) {
        //--- run a query To select services ---
        log("Database open to get Actions.");
        QString q = QString("SELECT TE_Name,EN_Direction,TE_Ret_Val,FK_Related_State_Variable from Action WHERE FK_Service IN (SELECT PK_Id from Action WHERE FK_Service = '%1' AND TE_Name='%2' AND BO_Deleted=0)  AND BO_Deleted=0").arg(service_id).arg(action_name);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
          log("Query Error" + query.lastError().text());
        else while (query.next()) {
            StringMap argument;
            argument["TE_Name"]        = query.value(0).toString();
            argument["EN_Direction"]   = query.value(1).toString();
            argument["TE_Ret_Val"]     = query.value(2).toString();
            argument["FK_Related_State_Variable"] = query.value(3).toString();
            log(QString( "Found arg %1\t%2\n").arg(argument["TE_Name"]).arg( argument["EN_Direction"] ));
            arguments << argument;
        }
        daba.close();
    }
    else
        //--- case erros happen ---
        log("Error opening database\n");

    //Return arguments
    return arguments;
}

StringMap Database::getStateVariable(int service_id,QString state_variable_name){
    //Open connection
    bool openned = daba.open();
    StringMap svar;
    if ( openned ) {
        //--- run a query To select State variable ---
        log("Database open to get State variable.");
        QString q = QString("SELECT EN_Reading_Circuit_Type,EN_Writing_Circuit_Type,IN_Reading_Circuit_Pin,IN_Writing_Circuit_Pin,EN_Reading_Circuit_Baudrate,EN_Writing_Circuit_Baudrate,EN_Data_Type from State_Variable WHERE FK_Service = '%1' AND TE_Name='%2' AND BO_Deleted=0").arg(service_id).arg(state_variable_name);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
          log("Query Error" + query.lastError().text());
        else while (query.next()) {
            StringMap argument;
            svar["EN_Reading_Circuit_Type"]     = query.value(0).toString();
            svar["EN_Writing_Circuit_Type"]     = query.value(1).toString();
            svar["IN_Reading_Circuit_Pin"]      = query.value(2).toString();
            svar["IN_Writing_Circuit_Pin"]      = query.value(3).toString();
            svar["EN_Reading_Circuit_Baudrate"] = query.value(4).toString();
            svar["EN_Writing_Circuit_Baudrate"] = query.value(5).toString();
            svar["EN_Data_Type"]                = query.value(6).toString();
            log(QString( "Found svar %1\\n").arg(state_variable_name));
            break;
        }
        daba.close();
    }
    else
        //--- case erros happen ---
        log("Error opening database\n");

    //Return arguments
    return svar;
}

QList<StringMap> Database::getInfo(QString zigbee_addr, QString pin_number){
    //Open connection
    bool openned = daba.open();
    QList<StringMap> arguments;
    if ( openned ) {
        //--- run a query To select services ---
        log("Database open to get Actions.");
        QString q = QString("SELECT Service.TE_Service_Id AS service_name, State_Variable.TE_Name AS svar_name, Device.PK_Id AS device_id FROM State_Variable INNER JOIN Service ON Service.PK_Id = State_Variable.FK_Service INNER JOIN Device ON Device.PK_Id = Service.FK_Device INNER JOIN Slave_Controller ON Slave_Controller.PK_Unic_Name LIKE Device.FK_Slave_Controller WHERE Slave_Controller.TE_Address LIKE \"%1\" AND State_Variable.IN_Writing_Circuit_Pin = %2 AND State_Variable.BO_Deleted = 0").arg(zigbee_addr).arg(pin_number);
        log(q);
        QSqlQuery query(q);
        if ( !query.isActive() )
          log("Query Error" + query.lastError().text());
        else while (query.next()) {
            StringMap argument;
            argument["service_name"]        = query.value(0).toString();
            argument["svar_name"]   = query.value(1).toString();
            argument["device_id"]     = query.value(2).toString();
            log(QString( "Found arg %1\t%2\n").arg(argument["service_name"]).arg( argument["svar_name"] ));
            arguments << argument;
        }
        daba.close();
    }
    else
        //--- case erros happen ---
        log("Error opening database\n");

    //Return arguments
    return arguments;
}

void log(QString text){
    qDebug() << "-----DEBUG----";
    qDebug() << QString("-------------- %1").arg(text);
    qDebug() << "--------------";
}
void log(QStringList text){
    qDebug() << "-----DEBUG----   vvvvvv Look down vvvvv";
    qDebug() << text;
    qDebug() << "--------------";
}
