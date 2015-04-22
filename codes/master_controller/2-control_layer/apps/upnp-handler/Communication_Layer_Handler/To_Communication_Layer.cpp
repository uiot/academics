#include "To_Communication_Layer.h"


//Socket ----------------------------------------------------------------------------------------------------


To_Communication_Layer::To_Communication_Layer( QString host_name_or_ip, QString socket_nickname, QObject *parent)
    : QObject(parent){
    ip = host_name_or_ip;
    name = socket_nickname;
    my_thread = new HiroThreadClient(host_name_or_ip,port, socket_nickname, this);
    connect(my_thread, SIGNAL(finished()), my_thread, SLOT(deleteLater()));
    my_thread->start();
}


QByteArray To_Communication_Layer::send_control_request(QString slavecontroller_address,QString pin_number, QString pin_type, QString baud_rate, QString data_type, QString data){
    return my_thread->send_control_request(slavecontroller_address,pin_number,pin_type,baud_rate,data_type,data);
}


//Thread --------------------------------------------------------------------------------------------------------------


HiroThreadClient::HiroThreadClient( QString host_name_or_ip, int host_port, QString socket_nickname, QObject *parent)
    : QThread(parent){
    ip = host_name_or_ip;
    port = host_port;
    name = socket_nickname;
}

void HiroThreadClient::run(){

}

bool HiroThreadClient::connect_to_communication_layer(){

    //Cria socket e tenta se conectar
    socket = new QTcpSocket();
    qDebug() << "Starting communication with COMM Layer. " << "Port:" << this->port << " IP: "<< this->ip;
    socket->connectToHost(this->ip, this->port);
    QTime time = QTime::currentTime();
    QString timeString = time.toString();

    //Waits 30 seconds before connect
    bool connected = socket->waitForConnected(30000);
    if(connected){
        qDebug() << QString("%1-%2-SUCCESS-Connected to Communication Layer.").arg(timeString).arg(name);
        return true;
    }
    //case it fails, starts over.
    else{
        time = QTime::currentTime();
        timeString = time.toString();
        qDebug() << QString("%1-%2-ERROR-COULD NOT CONNECT IN 30 SECONDS;").arg(timeString).arg(name);
        socket->close();
        socket->abort();
        free(socket);
        if(retries<3){
            retries++;
            return connect_to_communication_layer();
        }else{
            return false;
        }
    }
}

QByteArray HiroThreadClient::send_control_request(QString slavecontroller_address,QString pin_number, QString pin_type, QString baud_rate, QString data_type, QString data){
    bool connected = this->connect_to_communication_layer();
    if(connected){
        QTime time = QTime::currentTime();
        QTime timer;
        QString timeString = time.toString();
        QVariantMap requestJson;
        QJson::Serializer json_encoder;
        bool ok;
        bool fail;
        QByteArray response;

        //QString msg = QString("%1-%2-SUCCESS-Read-Com_Layer %3 bytes = %4\n\n").arg(timeString,name,QString("%1").arg(socket->bytesAvailable()),read_data);
        //qDebug() << msg;
        timer.start();
        requestJson.insert("slavecontroller_address",slavecontroller_address);
        requestJson.insert("pin_number",pin_number);
        requestJson.insert("pin_type",pin_type);
        requestJson.insert("baud_rate",baud_rate);
        requestJson.insert("data_type",data_type);
        requestJson.insert("data",data);

        socket->write(json_encoder.serialize(requestJson,&ok)+"\n");

        //aguarda ate 5 dias para que o host escreva algo no socket
        socket->waitForBytesWritten(5*24*60*60*1000);

        //aguarda ate 30 segundos para os bytes estarem disponiveis para leitura
        socket->waitForReadyRead(30000);

        while(socket->bytesAvailable() < 30){
            usleep(2000);
            if(timer.elapsed()>70*1000)//if elapsed more then 30s waiting for response, try again.
                break;
        }
        usleep(1000);
        if(socket->bytesAvailable() > 50){
            time = QTime::currentTime();
            timeString = time.toString();
            response = socket->readAll();
            fail = false;
            QString msg = QString("%1-%2-ATTEMPT%3-SUCCESS-Read %4 bytes = %5\n").arg(timeString,name,QString("%1").arg(retries),QString("%1").arg(socket->bytesAvailable()),response);
            qDebug() << msg;
        }else{
            fail = true;
            time = QTime::currentTime();
            timeString = time.toString();
            qDebug() << QString("%1-%2-ATTEMPT%3-ERROR-RESPONSE NOT RECEIVED IN 30 SECONDS\n").arg(timeString).arg(name).arg(retries);
        }

        socket->close();
        socket->abort();
        free(socket);
        if(fail && retries < 10){
            retries++;
            send_control_request(slavecontroller_address,pin_number,pin_type,baud_rate,data_type,data);
        }
        return response;
    }else{
        return "{\"status\":\"fail\",\"message\":\"coudl not reach Communication Layer.\"}";
    }
}

