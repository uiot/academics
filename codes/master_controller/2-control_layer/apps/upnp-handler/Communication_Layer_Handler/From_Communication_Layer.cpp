#include "From_Communication_Layer.h"

using namespace Herqq::Upnp;

From_Communication_Layer::From_Communication_Layer(QObject *parent) : QTcpServer(parent){

}

void From_Communication_Layer::StartServer()
{
    if(!this->listen(QHostAddress::Any,config_port))
    {
        qDebug() << "Could not start server";
    }
    else
    {
        qDebug() << "Listening...";
    }
}
QByteArray From_Communication_Layer::Process_Request(QByteArray request_json_string){
    int socketDescriptor = 0;
    qDebug() << " Executing straight action...";
    Thread_Handler_2 *thread = new Thread_Handler_2(socketDescriptor, this);
    thread->services = &services;
    thread->request = request_json_string;
    return thread->Process_Request();
}
void From_Communication_Layer::incomingConnection(int socketDescriptor)
{
    qDebug() << socketDescriptor << " Connecting...";
    Thread_Handler_2 *thread = new Thread_Handler_2(socketDescriptor, this);
    connect(thread, SIGNAL(finished()), thread, SLOT(deleteLater()));
    thread->services = &services;
    thread->start();

}


QByteArray Thread_Handler_2::Process_Request(){
    QByteArray request_json_string = this->request;
    qDebug() <<request_json_string;
    QJson::Parser json_decoder;
    QJson::Serializer json_encoder;
    QVariantMap outJson;
    bool ok;
    int i;
    try{
        //converts json to map
        QVariantMap inJson = json_decoder.parse(request_json_string, &ok).toMap();
        if(!ok){
            outJson.insert("status","fail");
            outJson.insert("message","unable to decode JSON");
            return json_encoder.serialize(outJson, &ok);
        }
        //Receives data from map
        QString slavecontroller_address = inJson["slavecontroller_address"].toString();
        QString pin_number = inJson["pin_number"].toString();
        QString order = inJson["order"].toString();
        QString read_value = inJson["data"].toString();
        QList<StringMap> db_data = db.getInfo(slavecontroller_address,pin_number);

        for (i = 0; i < db_data.size(); i++) {
            QString service_id = db_data[i]["service_name"];
            QString device_id = db_data[i]["device_id"];
            QString svar_name = db_data[i]["svar_name"];

            //gets HUPnP action
            if(service_id != QString("") && device_id != QString("")){
                HServerDevice* services_holder = services->value(device_id);
                HServerService* service = services_holder->serviceById(HServiceId(service_id));
                HServerStateVariables svar = service->stateVariables();
                svar.value(svar_name)->setValue(read_value);
            }else{
                outJson.insert("status","fail");
                outJson.insert("message","Could not find all necessary info.");
                return json_encoder.serialize(outJson, &ok);
            }
        }
    }catch(std::exception& e){
        outJson.insert("status","fail");
        outJson.insert("message",QString("unknow error:%1").arg(e.what()));
        return json_encoder.serialize(outJson, &ok);
    }
    outJson.insert("status","success");
    outJson.insert("message",QString("All %1 related variables had their value changed.").arg(i));
    return json_encoder.serialize(outJson, &ok);
}
Thread_Handler_2::Thread_Handler_2(int id, QObject *parent) :QThread(parent)
{
    if(id >0)
        this->socketDescriptor = id;
}

void Thread_Handler_2::run()
{
    qDebug() << socketDescriptor << " Starting thread";
    socket = new QTcpSocket();
    if(!socket->setSocketDescriptor(this->socketDescriptor))
    {
        emit error(socket->error());
        return;
    }

    connect(socket, SIGNAL(readyRead()), this, SLOT(readyRead()),Qt::DirectConnection);
    connect(socket, SIGNAL(disconnected()), this, SLOT(disconnected()),Qt::DirectConnection);

    qDebug() << socketDescriptor << " Client connected";

    // make this thread a loop
    exec();
}

void Thread_Handler_2::readyRead()
{
    //receives data
    request = socket->readLine();
    qDebug() << socketDescriptor << " Data in: " << request;
    //proccesses request
    QByteArray response;
    response.append(this->Process_Request());
    qDebug() << socketDescriptor << " Data out: " << response;
    //Send response
    socket->write(response);
    socket->waitForBytesWritten();
    socket->disconnect();
    socket->deleteLater();
    exit(0);
}

void Thread_Handler_2::disconnected()
{
    qDebug() << socketDescriptor << " Disconnected";
    socket->deleteLater();
    exit(0);
}
