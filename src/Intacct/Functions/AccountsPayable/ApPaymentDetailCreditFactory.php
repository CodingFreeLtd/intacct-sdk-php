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

class ApPaymentDetailCreditFactory
{
    public function create(
        $type,
        $recordNo,
        $lineRecordNo = null,
        $transactionAmount = null
    ): AbstractApPaymentDetailCredit {

        switch ($type) {
            case AbstractApPaymentDetailCredit::NEGATIVE_BILL:
                $detailCredit = new ApPaymentDetailNegativeBill();
                break;
            case AbstractApPaymentDetailCredit::DEBIT_MEMO:
                $detailCredit = new ApPaymentDetailDebitMemo();
                break;
            case AbstractApPaymentDetailCredit::ADVANCE:
                $detailCredit = new ApPaymentDetailAdvance();
                break;
            default:
                throw new \InvalidArgumentException("Credit $type doesn't exist.");
        }

        $detailCredit->recordNo = $recordNo;
        $detailCredit->lineRecordNo = $lineRecordNo;
        $detailCredit->transactionAmount = $transactionAmount;

        return $detailCredit;
    }
}