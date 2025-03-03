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

namespace Intacct\Functions\GeneralLedger;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

/**
 * Create a new statistical account record
 */
class StatisticalAccountCreate extends AbstractStatisticalAccount
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
        $xml->startElement('STATACCOUNT');

        if (!$this->getAccountNo()) {
            throw new InvalidArgumentException('Account No is required for create');
        }
        $xml->writeElement('ACCOUNTNO', $this->getAccountNo(), true);
        if (!$this->getTitle()) {
            throw new InvalidArgumentException('Title is required for create');
        }
        $xml->writeElement('TITLE', $this->getTitle(), true);
        $xml->writeElement('ACCOUNTTYPE', $this->getReportType(), true);

        $xml->writeElement('CATEGORY', $this->getSystemCategory());

        if ($this->isActive() === true) {
            $xml->writeElement('STATUS', 'active');
        } elseif ($this->isActive() === false) {
            $xml->writeElement('STATUS', 'inactive');
        }

        $xml->writeElement('REQUIREDEPT', $this->isRequireDepartment());
        $xml->writeElement('REQUIRELOC', $this->isRequireLocation());
        $xml->writeElement('REQUIREPROJECT', $this->isRequireProject());
        $xml->writeElement('REQUIRECUSTOMER', $this->isRequireCustomer());
        $xml->writeElement('REQUIREVENDOR', $this->isRequireVendor());
        $xml->writeElement('REQUIREEMPLOYEE', $this->isRequireEmployee());
        $xml->writeElement('REQUIREITEM', $this->isRequireItem());
        $xml->writeElement('REQUIRECLASS', $this->isRequireClass());
        $xml->writeElement('REQUIRECONTRACT', $this->isRequireContract());
        $xml->writeElement('REQUIREWAREHOUSE', $this->isRequireWarehouse());

        $this->writeXmlImplicitCustomFields($xml);

        $xml->endElement(); //STATACCOUNT
        $xml->endElement(); //create

        $xml->endElement(); //function
    }
}