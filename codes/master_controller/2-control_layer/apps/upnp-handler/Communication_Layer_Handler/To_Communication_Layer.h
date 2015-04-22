#ifndef COMMUNICATION_LAYER_HANDLER_H
#define COMMUNICATION_LAYER_HANDLER_H

#include <QObject>
#include <QDebug>
#include <QTcpSocket>
#include <QAbstractSocket>

#include <QThread>
#include <QDateTime>

//QJson
#include <qjson/serializer.h>
#include <qjson/parser.h>

class HiroThreadClient : public QThread{
    Q_OBJECT
public:
    explicit HiroThreadClient(QString ip="127.0.0.1", int port=80, QString name = "SocketClient", QObject *parent = 0);
    QByteArray send_control_request(QString zigbee_addr,QString pin_number, QString pin_type, QString baudrate, QString data_type, QString data);
    void run();

signals:

public slots:

private:
    int retries = 0;
    QTcpSocket *socket;
    QString ip;
    int port;
    QString name;
    bool connect_to_communication_layer();

};

class To_Communication_Layer : public QObject{
    Q_OBJECT
public:
    explicit To_Communication_Layer(QString ip="127.0.0.1",  QString name = "CTRL_Client_to_send_request_to_COMM",QObject *parent = 0);
    QByteArray send_control_request(QString zigbee_addr,QString pin_number, QString pin_type, QString baudrate, QString data_type, QString data);
private:
    QString ip;
    HiroThreadClient* my_thread;
    int port = 12938;
    QString name;
};
#endif // COMMUNICATION_LAYER_HANDLER_H
