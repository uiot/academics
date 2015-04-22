/********************************************************************************
** Form generated from reading UI file 'genericinput.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_GENERICINPUT_H
#define UI_GENERICINPUT_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QHeaderView>
#include <QtGui/QLineEdit>
#include <QtGui/QVBoxLayout>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_GenericInput
{
public:
    QVBoxLayout *verticalLayout;
    QLineEdit *inputLineEdit;

    void setupUi(QWidget *GenericInput)
    {
        if (GenericInput->objectName().isEmpty())
            GenericInput->setObjectName(QString::fromUtf8("GenericInput"));
        GenericInput->resize(241, 38);
        verticalLayout = new QVBoxLayout(GenericInput);
        verticalLayout->setSpacing(0);
        verticalLayout->setContentsMargins(0, 0, 0, 0);
        verticalLayout->setObjectName(QString::fromUtf8("verticalLayout"));
        inputLineEdit = new QLineEdit(GenericInput);
        inputLineEdit->setObjectName(QString::fromUtf8("inputLineEdit"));
        QSizePolicy sizePolicy(QSizePolicy::Expanding, QSizePolicy::Expanding);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(inputLineEdit->sizePolicy().hasHeightForWidth());
        inputLineEdit->setSizePolicy(sizePolicy);

        verticalLayout->addWidget(inputLineEdit);


        retranslateUi(GenericInput);

        QMetaObject::connectSlotsByName(GenericInput);
    } // setupUi

    void retranslateUi(QWidget *GenericInput)
    {
        GenericInput->setWindowTitle(QApplication::translate("GenericInput", "Form", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class GenericInput: public Ui_GenericInput {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_GENERICINPUT_H
