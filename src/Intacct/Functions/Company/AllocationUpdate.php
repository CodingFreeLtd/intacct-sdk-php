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

namespace Intacct\Functions\Company;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

/**
 * Update an existing allocation record
 */
class AllocationUpdate extends AbstractAllocation
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

        $xml->startElement('update');
        $xml->startElement('ALLOCATION');

        if (!$this->getAllocationId()) {
            throw new InvalidArgumentException('Allocation ID is required for update');
        }
        $xml->writeElement('ALLOCATIONID', $this->getAllocationId(), true);

        $xml->writeElement('TYPE', $this->getAllocateBy());
        $xml->writeElement('DESCRIPTION', $this->getDescription());
        $xml->writeElement('DOCNUMBER', $this->getDocumentNo());
        $xml->writeElement('SUPDOCID', $this->getAttachmentsId());

        if ($this->isActive() === true) {
            $xml->writeElement('STATUS', 'active');
        } elseif ($this->isActive() === false) {
            $xml->writeElement('STATUS', 'inactive');
        }

        if (count($this->getLines()) > 0) {
            // Completely replaces any existing lines

            $xml->startElement('ALLOCATIONENTRIES');

            foreach ($this->getLines() as $allocationLine) {
                $allocationLine->writeXml($xml);
            }

            $xml->endElement(); //ALLOCATIONENTRIES
        }

        $xml->endElement(); //ALLOCATION
        $xml->endElement(); //update

        $xml->endElement(); //function
    }
}