<?php
/**
  *
  * Copyright (c) 2009, Persistent Systems Limited
  *
  * Redistribution and use, with or without modification, are permitted
  *  provided that the following  conditions are met:
  *   - Redistributions of source code must retain the above copyright notice,
  *     this list of conditions and the following disclaimer.
  *   - Neither the name of Persistent Systems Limited nor the names of its contributors
  *     may be used to endorse or promote products derived from this software
  *     without specific prior written permission.
  *
  * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
  * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
  * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
  * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
  * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
  * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
  * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
  * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
  * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
  * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
  * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
  */

/**
  *
  * class CatalogItem
  */
class CatalogItem implements ISSRSBaseType
{

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $ID;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $Name;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $Path;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $VirtualPath;

        /**         
         * @type : ItemTypeEnum
         * @IsCollection : false
         * @IsEnum : true
         */
        public $Type;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $Size;

        /**         
         * @type : bool
         * @IsCollection : false
         */
        public $SizeSpecified;

        /**
         * @type : string
         * @IsCollection : false
         */
        public $Description;

        /**         
         * @type : bool
         * @IsCollection : false
         */
        public $Hidden;

        /**         
         * @type : bool
         * @IsCollection : false
         */
        public $HiddenSpecified;

        /**         
         * @type : DateTime
         * @IsCollection : false
         */
        public $CreationDate;

        /**         
         * @type : bool
         * @IsCollection : false
         */
        public $CreationDateSpecified;

        /**         
         * @type : DateTime
         * @IsCollection : false
         */
        public $ModifiedDate;

        /**         
         * @type : bool
         * @IsCollection : false
         */
        public $ModifiedDateSpecified;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $CreatedBy;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $ModifiedBy;

        /**         
         * @type : string
         * @IsCollection : false
         */
        public $MimeType;

        /**         
         * @type : DateTime
         * @IsCollection : false
         */
        public $ExecutionDate;

        /**         
         * @type : bool
         * @IsCollection : false
         */

        public function CatalogItem()
        {
            $this->SizeSpecified = false;
            $this->HiddenSpecified = false;
            $this->CreationDateSpecified = false;
            $this->ModifiedDateSpecified = false;
        }

        public function Initialize()
        {
            $this->SizeSpecified = isset($this->Size);
            $this->HiddenSpecified = is_bool($this->Hidden);
            $this->CreationDateSpecified = isset($this->CreationDate);
            $this->ModifiedDateSpecified = isset($this->ModifiedDateSpecified);
        }

        public function FromStdObject($stdObject)
        {
            throw new PReportException('Not Implemented');
        }
    }
?>
