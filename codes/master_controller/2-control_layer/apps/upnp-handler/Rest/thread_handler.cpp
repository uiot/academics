#include "thread_handler.h"
#include "QDebug"

using namespace Herqq::Upnp;
bool is_zigbee_free = true;
QByteArray Thread_Handler::Process_Request(){
    QByteArray request_json_string = this->request;
    qDebug() <<request_json_string;
    QJson::Parser json_decoder;
    QJson::Serializer json_encoder;
    QVariantMap outJson;
    bool ok;
    try{

        //converts json to map
        QVariantMap inJson = json_decoder.parse(request_json_string, &ok).toMap();
        if(!ok){
            outJson.insert("status","fail");
            outJson.insert("message","unable to decode JSON");
            return json_encoder.serialize(outJson, &ok);
        }

        //Receives data from map
        QString device_id = inJson["device"].toString();
        QString service_id = inJson["service"].toString();
        QString action_name = inJson["action"].toString();

        QMap<QString, QVariant> args = inJson["arguments"].toMap();

        //gets HUPnP action
        if(!services->contains(device_id)){
            outJson.insert("status","fail");
            outJson.insert("message",QString("Unknown Device '%1'.").arg(device_id));
            return json_encoder.serialize(outJson, &ok);
        }

        HServerDevice* services_holder = services->value(device_id);
        HServiceId s_id = HServiceId(db.getServiceIdString(service_id));

        if(services_holder->serviceById(s_id)==0){
            outJson.insert("status","fail");
            outJson.insert("message",QString("Unknown Service '%1'.").arg(service_id));
            return json_encoder.serialize(outJson, &ok);
        }

        HServerService* service = services_holder->serviceById(s_id);
        HServerActions actions =  service->actions();
        if(actions.contains(action_name)){
            action = actions[action_name];
        }else{
            outJson.insert("status","fail");
            outJson.insert("message",QString("Invalid action name '%1'.").arg(action_name));
            return json_encoder.serialize(outJson, &ok);
        }

        //Setup input arguments for HUPnP action
        HActionArguments inputArgs = action->info().inputArguments();
        for(qint32 i = 0; i < inputArgs.size(); ++i){
            //Gets arg
            HActionArgument inputArg = inputArgs[i];
            QVariant data = args[inputArg.name()];
            QString data_type = HUpnpDataTypes::toString(inputArg.dataType());
            //sets arg
            if (inputArg.isValidValue(data)){
                ok = inputArg.setValue(data);
            }else{
                outJson.insert("status","fail");
                outJson.insert("message",QString("Wrong type for argument %1. It should be a %2.").arg(inputArg.name()).arg(data_type));
                return json_encoder.serialize(outJson, &ok);
            }
        }

        //invoke HUPnP action
        HActionArguments outArgs;
        qint32 retVal = action->invoke(action_name,inputArgs,&outArgs);

        //treats output args and sends final response
        if (retVal == 200 /*HServerAction::Success*/)
        {
            QMap<QString, QVariant> args;
            for(qint32 i = 0; i < outArgs.size(); ++i){
                HActionArgument outArg = outArgs[i];
                args.insert(outArg.name(),outArg.value());
            }
            outJson.insert("status","success");
            outJson.insert("message",QString("action invoked with success."));
            outJson.insert("outArgs",args);

            return json_encoder.serialize(outJson, &ok);
        }else{
            outJson.insert("status","fail");
            outJson.insert("message",QString("Internal error."));
            return json_encoder.serialize(outJson, &ok);
        }

    }catch(std::exception& e){
        outJson.insert("status","fail");
        outJson.insert("message",QString("unknow error:%1").arg(e.what()));
        return json_encoder.serialize(outJson, &ok);
    }
}
Thread_Handler::Thread_Handler(int id, QObject *parent) :QThread(parent)
{
    if(id >0)
        this->socketDescriptor = id;
}

void Thread_Handler::run()
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

void Thread_Handler::readyRead()
{
    //receives data
    request = socket->readLine();
    qDebug() << socketDescriptor << " <Data in>\n " << request << " \n </Data in> " ;
    //proccesses request
    while(!is_zigbee_free){
        usleep(1000);
    }
    is_zigbee_free = false;
    QByteArray response;
    response.append(this->Process_Request());
    is_zigbee_free = true;
    qDebug() << socketDescriptor << " Data out: " << response;
    //Send response
    socket->write(response);
    socket->waitForBytesWritten();
    socket->disconnect();
    socket->deleteLater();
    exit(0);
}

void Thread_Handler::disconnected()
{
    qDebug() << socketDescriptor << " Disconnected";
    socket->deleteLater();
    exit(0);
}
