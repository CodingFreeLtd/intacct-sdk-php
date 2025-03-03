<?php

declare(strict_types=1);

/**
 * Copyright 2021 Sage Intacct, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"). You may not
 * use this file except in compliance with the License. You may obtain a copy
 * of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "LICENSE" file accompanying this file. This file is distributed on
 * an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Intacct\Functions\AccountsReceivable;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

/**
 * Create a new customer record
 */
class CustomerCreate extends AbstractCustomer
{

    /**
     * Write the function block XML
     *
     * @param XMLWriter $xml
     * @throw InvalidArgumentException
     */
    public function writeXml(?XMLWriter &$xml)
    {
        if(!$xml instanceof XMLWriter){
            $xml = new XMLWriter();
        }
        $xml->startElement('function');
        $xml->writeAttribute('controlid', $this->getControlId());

        $xml->startElement('create');
        $xml->startElement('CUSTOMER');

        // Customer ID is not required if auto-numbering is configured in module
        $xml->writeElement('CUSTOMERID', $this->getCustomerId());

        if (!$this->getCustomerName()) {
            throw new InvalidArgumentException('Customer Name is required for create');
        }
        $xml->writeElement('NAME', $this->getCustomerName(), true);

        $xml->startElement('DISPLAYCONTACT');

        // CONTACTNAME is auto created as '[CustomerName](C[CustomerID])'

        if (!$this->getPrintAs()) {
            // Use the vendor name if the print as is missing
            $xml->writeElement('PRINTAS', $this->getCustomerName(), true);
        } else {
            $xml->writeElement('PRINTAS', $this->getPrintAs(), true);
        }

        $xml->writeElement('COMPANYNAME', $this->getCompanyName());
        $xml->writeElement('TAXABLE', $this->isTaxable());
        // TAXID is passed in with VENDOR element below
        $xml->writeElement('TAXGROUP', $this->getContactTaxGroupName());
        $xml->writeElement('PREFIX', $this->getPrefix());
        $xml->writeElement('FIRSTNAME', $this->getFirstName());
        $xml->writeElement('LASTNAME', $this->getLastName());
        $xml->writeElement('INITIAL', $this->getMiddleName());
        $xml->writeElement('PHONE1', $this->getPrimaryPhoneNo());
        $xml->writeElement('PHONE2', $this->getSecondaryPhoneNo());
        $xml->writeElement('CELLPHONE', $this->getCellularPhoneNo());
        $xml->writeElement('PAGER', $this->getPagerNo());
        $xml->writeElement('FAX', $this->getFaxNo());
        $xml->writeElement('EMAIL1', $this->getPrimaryEmailAddress());
        $xml->writeElement('EMAIL2', $this->getSecondaryEmailAddress());
        $xml->writeElement('URL1', $this->getPrimaryUrl());
        $xml->writeElement('URL2', $this->getSecondaryUrl());

        $this->writeXmlMailAddress($xml);

        $xml->endElement(); //DISPLAYCONTACT

        $xml->writeElement('ONETIME', $this->isOneTime());

        if ($this->isActive() === true) {
            $xml->writeElement('STATUS', 'active');
        } elseif ($this->isActive() === false) {
            $xml->writeElement('STATUS', 'inactive');
        }

        $xml->writeElement('HIDEDISPLAYCONTACT', $this->isExcludedFromContactList());
        $xml->writeElement('CUSTTYPE', $this->getCustomerTypeId());
        $xml->writeElement('CUSTREPID', $this->getSalesRepEmployeeId());
        $xml->writeElement('PARENTID', $this->getParentCustomerId());
        $xml->writeElement('GLGROUP', $this->getGlGroupName());
        $xml->writeElement('TERRITORYID', $this->getTerritoryId());
        $xml->writeElement('SUPDOCID', $this->getAttachmentsId());
        $xml->writeElement('TERMNAME', $this->getPaymentTerm());
        $xml->writeElement('OFFSETGLACCOUNTNO', $this->getOffsetArGlAccountNo());
        $xml->writeElement('ARACCOUNT', $this->getDefaultRevenueGlAccountNo());
        $xml->writeElement('SHIPPINGMETHOD', $this->getShippingMethod());
        $xml->writeElement('RESALENO', $this->getResaleNumber());
        $xml->writeElement('TAXID', $this->getTaxId());
        $xml->writeElement('CREDITLIMIT', $this->getCreditLimit());
        $xml->writeElement('ONHOLD', $this->isOnHold());
        $xml->writeElement('DELIVERYOPTIONS', $this->getDeliveryMethod());
        $xml->writeElement('CUSTMESSAGEID', $this->getDefaultInvoiceMessage());
        $xml->writeElement('COMMENTS', $this->getComments());
        $xml->writeElement('CURRENCY', $this->getDefaultCurrency());

        $xml->writeElement('ARINVOICEPRINTTEMPLATEID', $this->getPrintOptionArInvoiceTemplateName());
        $xml->writeElement('OEQUOTEPRINTTEMPLATEID', $this->getPrintOptionOeQuoteTemplateName());
        $xml->writeElement('OEORDERPRINTTEMPLATEID', $this->getPrintOptionOeOrderTemplateName());
        $xml->writeElement('OELISTPRINTTEMPLATEID', $this->getPrintOptionOeListTemplateName());
        $xml->writeElement('OEINVOICEPRINTTEMPLATEID', $this->getPrintOptionOeInvoiceTemplateName());
        $xml->writeElement('OEADJPRINTTEMPLATEID', $this->getPrintOptionOeAdjustmentTemplateName());
        $xml->writeElement('OEOTHERPRINTTEMPLATEID', $this->getPrintOptionOeOtherTemplateName());

        if ($this->getPrimaryContactName()) {
            $xml->startElement('CONTACTINFO');
            $xml->writeElement('CONTACTNAME', $this->getPrimaryContactName(), true);
            $xml->endElement(); //CONTACTINFO
        }

        if ($this->getBillToContactName()) {
            $xml->startElement('BILLTO');
            $xml->writeElement('CONTACTNAME', $this->getBillToContactName(), true);
            $xml->endElement(); //BILLTO
        }

        if ($this->getShipToContactName()) {
            $xml->startElement('SHIPTO');
            $xml->writeElement('CONTACTNAME', $this->getShipToContactName(), true);
            $xml->endElement(); //SHIPTO
        }

        $xml->writeElement('OBJECTRESTRICTION', $this->getRestrictionType());
        if (count($this->getRestrictedLocations()) > 0) {
            $xml->writeElement('RESTRICTEDLOCATIONS', $this->getRestrictedLocations());
        }
        if (count($this->getRestrictedDepartments()) > 0) {
            $xml->writeElement('RESTRICTEDDEPARTMENTS', $this->getRestrictedDepartments());
        }

        // TODO Salesforce tab

        $this->writeXmlImplicitCustomFields($xml);

        $xml->endElement(); //CUSTOMER
        $xml->endElement(); //create

        $xml->endElement(); //function
    }
}