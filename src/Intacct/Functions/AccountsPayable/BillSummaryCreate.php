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

namespace Intacct\Functions\AccountsPayable;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

/**
 * Create a new accounts payable bill summary record
 */
class BillSummaryCreate extends AbstractBillSummary
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

        $xml->startElement('create_billbatch');

        if (!$this->getTitle()) {
            throw new InvalidArgumentException('Title is required for create');
        }
        $xml->writeElement('batchtitle', $this->getTitle(), true);

        if (!$this->getGlPostingDate()) {
            throw new InvalidArgumentException('GL Posting Date is required for create');
        }
        $xml->startElement('datecreated');
        $xml->writeDateSplitElements($this->getGlPostingDate(), true);
        $xml->endElement(); //datecreated

        /*if (count($this->getBills()) > 0) {
            foreach ($this->getBills() as $bill) {
                $bill->writeXml($xml);
            }
        }*/

        $xml->endElement(); //create_billbatch

        $xml->endElement(); //function
    }
}