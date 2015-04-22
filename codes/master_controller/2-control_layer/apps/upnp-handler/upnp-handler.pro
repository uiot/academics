TEMPLATE = app
TARGET   = "[UIoT] Control Layer and UPnP API"
QT      += network testlib xml sql
CONFIG  += warn_on
#CONFIG -= app_bundle

ICON = $$PWD/images/logos/logo.icns
INCLUDEPATH += ../../hupnp/include

#MYSQL
##/usr/local/Cellar/mysql/5.6.16/lib/release/
##/usr/local/Cellar/mysql/5.6.16/include/mysql
##MYSQL
##QJson
win32:CONFIG(release, debug|release): LIBS += -L/usr/local/Cellar/qjson/0.8.1/lib/release/ -lqjson
else:win32:CONFIG(debug, debug|release): LIBS += -L/usr/local/Cellar/qjson/0.8.1/lib/debug/ -lqjson
else:unix: LIBS += -L/usr/local/Cellar/qjson/0.8.1/lib/ -lqjson

INCLUDEPATH += /usr/local/Cellar/qjson/0.8.1/include
DEPENDPATH += /usr/local/Cellar/qjson/0.8.1/include
##/QJson
LIBS += -L"../../hupnp/bin" -lHUpnp \
        -L"../../hupnp/lib/qtsoap-2.7-opensource/lib"

win32 {
    debug {
        LIBS += -lQtSolutions_SOAP-2.7d
    }
    else {
        LIBS += -lQtSolutions_SOAP-2.7
    }

    LIBS += -lws2_32

    DESCRIPTIONS = $$PWD\\images\\logos
    DESCRIPTIONS = $${replace(LOGOS, /, \\)}
    QMAKE_POST_LINK += xcopy $$LOGOS bin\\images\\logos /E /Y /C /I $$escape_expand(\\n\\t)
    QMAKE_POST_LINK += copy ..\\..\\hupnp\\bin\\* bin /Y
}
else {
    LIBS += -lQtSolutions_SOAP-2.7
    !macx:QMAKE_LFLAGS += -Wl,--rpath=\\\$\$ORIGIN

    QMAKE_POST_LINK += cp -Rf $$PWD/images/logos bin &
    QMAKE_POST_LINK += cp -Rf ../../hupnp/bin/* bin
}

macx {
  CONFIG -= app_bundle
  #CONFIG += x86 x86_64
}

OBJECTS_DIR = obj
MOC_DIR = obj

DESTDIR = ./bin

HEADERS += \
    Main_Window/mainwindow.h \
    Devices/device_window.h \
    Control_Point/controlpoint_window.h \
    Control_Point/controlpoint_navigator.h \
    Control_Point/controlpoint_navigatoritem.h \
    Control_Point/dataitem_display.h \
    Control_Point/invokeactiondialog.h \
    Control_Point/allowedvaluelist_input.h \
    Control_Point/genericinput.h \
    Control_Point/i_dataholder.h \
    Database/database.h \
    Rest/rest_handler.h \
    Rest/thread_handler.h \
    Communication_Layer_Handler/From_Communication_Layer.h \
    Communication_Layer_Handler/To_Communication_Layer.h

SOURCES += \
    Database/database.cpp \
    main.cpp \
    Main_Window/mainwindow.cpp \
    Control_Point/controlpoint_window.cpp \
    Devices/device_window.cpp \
    Control_Point/controlpoint_navigator.cpp \
    Control_Point/controlpoint_navigatoritem.cpp \
    Control_Point/dataitem_display.cpp \
    Control_Point/invokeactiondialog.cpp \
    Control_Point/allowedvaluelist_input.cpp \
    Control_Point/genericinput.cpp \
    Control_Point/i_dataholder.cpp \
    Rest/rest_handler.cpp \
    Rest/thread_handler.cpp \
    Communication_Layer_Handler/To_Communication_Layer.cpp \
    Communication_Layer_Handler/From_Communication_Layer.cpp

FORMS += \
    Main_Window/mainwindow.ui \
    Control_Point/controlpoint.ui \
    Devices/device_window.ui \
    Control_Point/invokeactiondialog.ui \
    Control_Point/genericinput.ui \
    Control_Point/allowedvaluelist_input.ui

OTHER_FILES +=
