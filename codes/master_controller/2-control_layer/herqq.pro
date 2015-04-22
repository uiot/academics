TEMPLATE = subdirs
CONFIG  += ordered
CONFIG += release

exists(hupnp/options.pri) {
    win32 {
        system(type nul > hupnp/options.pri)
    }
    else {
        system(echo "" > hupnp/options.pri)
    }
}

CONFIG(DISABLE_QTSOAP) {
    system(echo "CONFIG += DISABLE_QTSOAP" > hupnp/options.pri)
}
CONFIG(USE_QT_INSTALL_LOC) {
    system(echo "CONFIG += USE_QT_INSTALL_LOC" >> hupnp/options.pri)
}

!CONFIG(DISABLE_CORE) : SUBDIRS += hupnp
!CONFIG(DISABLE_TESTAPP) : SUBDIRS += apps/upnp-handler
