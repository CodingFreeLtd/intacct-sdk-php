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

namespace Intacct\Functions\OrderEntry;

use Intacct\Xml\XMLWriter;

/**
 * Create a new order entry transaction line record
 */
class OrderEntryTransactionLineCreate extends AbstractOrderEntryTransactionLine
{

    /**
     * @param XMLWriter $xml
     */
    public function writeXml(?XMLWriter &$xml)
    {
        if(!$xml instanceof XMLWriter){
            $xml = new XMLWriter();
        }
        $xml->startElement('sotransitem');

        $xml->writeElement('bundlenumber', $this->getBundleNumber());
        $xml->writeElement('itemid', $this->getItemId(), true);
        $xml->writeElement('itemdesc', $this->getItemDescription());
        $xml->writeElement('taxable', $this->isTaxable());
        $xml->writeElement('warehouseid', $this->getWarehouseId());
        $xml->writeElement('quantity', $this->getQuantity(), true);
        $xml->writeElement('unit', $this->getUnit());
        $xml->writeElement('discountpercent', $this->getDiscountPercent());
        $xml->writeElement('price', $this->getPrice());
        $xml->writeElement('discsurchargememo', $this->getDiscountSurchargeMemo());
        $xml->writeElement('locationid', $this->getLocationId());
        $xml->writeElement('departmentid', $this->getDepartmentId());
        $xml->writeElement('memo', $this->getMemo());

        if (count($this->getItemDetails()) > 0) {
            $xml->startElement('itemdetails');
            foreach ($this->getItemDetails() as $itemDetail) {
                $itemDetail->writeXml($xml);
            }
            $xml->endElement(); //itemdetails
        }

        $this->writeXmlExplicitCustomFields($xml);

        $xml->writeElement('revrectemplate', $this->getRevRecTemplate());

        if ($this->getRevRecStartDate()) {
            $xml->startElement('revrecstartdate');
            $xml->writeDateSplitElements($this->getRevRecStartDate(), true);
            $xml->endElement(); //revrecstartdate
        }

        if ($this->getRevRecEndDate()) {
            $xml->startElement('revrecenddate');
            $xml->writeDateSplitElements($this->getRevRecEndDate(), true);
            $xml->endElement(); //revrecenddate
        }

        $xml->writeElement('renewalmacro', $this->getRenewalMacro());
        $xml->writeElement('projectid', $this->getProjectId());
        $xml->writeElement('customerid', $this->getCustomerId());
        $xml->writeElement('vendorid', $this->getVendorId());
        $xml->writeElement('employeeid', $this->getEmployeeId());
        $xml->writeElement('classid', $this->getClassId());
        $xml->writeElement('contractid', $this->getContractId());
        if (isset($this->fulfillmentStatus)) {
            $this->fulfillmentStatus->writeXml($xml);
        }
        $xml->writeElement('taskno', $this->getTaskNumber());
        $xml->writeElement('billingtemplate', $this->getBillingTemplate());

        $xml->endElement(); //sotransitem
    }
}