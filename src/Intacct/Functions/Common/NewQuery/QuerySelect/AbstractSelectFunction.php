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

namespace Intacct\Functions\Common\NewQuery\QuerySelect;

use Intacct\Xml\XMLWriter;

abstract class AbstractSelectFunction implements SelectInterface
{

    /** @var string */
    const AVERAGE = 'avg';

    /** @var string */
    const MINIMUM = 'min';

    /** @var string */
    const MAXIMUM = 'max';

    /** @var string */
    const COUNT = 'count';

    /** @var string */
    const SUM = 'sum';

    /** @var string $field */
    private $field;

    /**
     * AbstractSelectFunction constructor.
     *
     * @param string $field
     */
    public function __construct(string $field = '')
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    abstract public function getFunctionName(): string;

    /**
     * @param XMLWriter &$xml
     */
    public function writeXml(?XMLWriter &$xml)
    {
        if(!$xml instanceof XMLWriter){
            $xml = new XMLWriter();
        }
        $xml->writeElement($this->getFunctionName(), $this->field, false);
    }
}