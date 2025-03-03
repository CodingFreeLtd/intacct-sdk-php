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

namespace Intacct\Functions\Common\NewQuery\QueryOrderBy;

use InvalidArgumentException;

class OrderBuilder
{

    /** @var OrderInterface[] */
    private $orders;

    /**
     * SelectBuilder constructor.
     */
    public function __construct()
    {
        $this->orders = [];
    }

    /**
     * @param string $fieldName
     *
     * @return OrderBuilder
     */
    public function ascending(string $fieldName = ''): OrderBuilder
    {
        $this->validate($fieldName);
        $currentOrderField = new OrderAscending($fieldName);
        $this->orders[] = $currentOrderField;

        return $this;
    }

    /**
     * @param string $fieldName
     *
     * @return OrderBuilder
     */
    public function descending(string $fieldName = ''): OrderBuilder
    {
        $this->validate($fieldName);
        $currentOrderField = new OrderDescending($fieldName);
        $this->orders[] = $currentOrderField;

        return $this;
    }

    /**
     * @param $fieldName
     * @throws InvalidArgumentException
     */
    private function validate($fieldName)
    {
        if (!$fieldName) {
            throw new InvalidArgumentException(
                'Field name for field cannot be empty or null. Provide a field for the builder.'
            );
        }
    }

    /**
     * @return OrderInterface[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }
}