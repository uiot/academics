/********************************************************************************
** Form generated from reading UI file 'allowedvaluelist_input.ui'
**
** Created by: Qt User Interface Compiler version 4.8.6
**
** WARNING! All changes made in this file will be lost when recompiling UI file!
********************************************************************************/

#ifndef UI_ALLOWEDVALUELIST_INPUT_H
#define UI_ALLOWEDVALUELIST_INPUT_H

#include <QtCore/QVariant>
#include <QtGui/QAction>
#include <QtGui/QApplication>
#include <QtGui/QButtonGroup>
#include <QtGui/QComboBox>
#include <QtGui/QHBoxLayout>
#include <QtGui/QHeaderView>
#include <QtGui/QWidget>

QT_BEGIN_NAMESPACE

class Ui_AllowedValueListInput
{
public:
    QHBoxLayout *horizontalLayout;
    QComboBox *argumentValuescomboBox;

    void setupUi(QWidget *AllowedValueListInput)
    {
        if (AllowedValueListInput->objectName().isEmpty())
            AllowedValueListInput->setObjectName(QString::fromUtf8("AllowedValueListInput"));
        AllowedValueListInput->resize(87, 23);
        QSizePolicy sizePolicy(QSizePolicy::Expanding, QSizePolicy::Expanding);
        sizePolicy.setHorizontalStretch(0);
        sizePolicy.setVerticalStretch(0);
        sizePolicy.setHeightForWidth(AllowedValueListInput->sizePolicy().hasHeightForWidth());
        AllowedValueListInput->setSizePolicy(sizePolicy);
        horizontalLayout = new QHBoxLayout(AllowedValueListInput);
        horizontalLayout->setSpacing(0);
        horizontalLayout->setContentsMargins(0, 0, 0, 0);
        horizontalLayout->setObjectName(QString::fromUtf8("horizontalLayout"));
        argumentValuescomboBox = new QComboBox(AllowedValueListInput);
        argumentValuescomboBox->setObjectName(QString::fromUtf8("argumentValuescomboBox"));
        QSizePolicy sizePolicy1(QSizePolicy::Expanding, QSizePolicy::Fixed);
        sizePolicy1.setHorizontalStretch(0);
        sizePolicy1.setVerticalStretch(0);
        sizePolicy1.setHeightForWidth(argumentValuescomboBox->sizePolicy().hasHeightForWidth());
        argumentValuescomboBox->setSizePolicy(sizePolicy1);
        argumentValuescomboBox->setSizeAdjustPolicy(QComboBox::AdjustToContents);
        argumentValuescomboBox->setFrame(false);

        horizontalLayout->addWidget(argumentValuescomboBox);


        retranslateUi(AllowedValueListInput);

        QMetaObject::connectSlotsByName(AllowedValueListInput);
    } // setupUi

    void retranslateUi(QWidget *AllowedValueListInput)
    {
        AllowedValueListInput->setWindowTitle(QApplication::translate("AllowedValueListInput", "Form", 0, QApplication::UnicodeUTF8));
    } // retranslateUi

};

namespace Ui {
    class AllowedValueListInput: public Ui_AllowedValueListInput {};
} // namespace Ui

QT_END_NAMESPACE

#endif // UI_ALLOWEDVALUELIST_INPUT_H
