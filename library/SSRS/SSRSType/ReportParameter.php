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
  * class ReportParameter
  */
class ReportParameter implements ISSRSBaseType
{

    public function ReportParameter()   
    {
        $this->TypeSpecified = false;
        $this->NullableSpecified = false;
        $this->AllowBlankSpecified = false;
        $this->MultiValueSpecified = false;
        $this->QueryParameterSpecified = false;
        $this->PromptUserSpecified = false;
        $this->ValidValuesQueryBasedSpecified = false;
        $this->DefaultValuesQueryBasedSpecified = false;
        $this->StateSpecified = false;
    }
    
    /**
     * @type : string
     * @IsCollection : false
     */
    public $Name;

    /**
     * @type : ParameterTypeEnum
     * @IsCollection : false
     * @IsEnum : true
     */
    public $Type;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $TypeSpecified;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $Nullable;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $NullableSpecified;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $AllowBlank;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $AllowBlankSpecified;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $MultiValue;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $MultiValueSpecified;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $QueryParameter;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $QueryParameterSpecified;

    /**
     * @type : string
     * @IsCollection : false
     */
    public $Prompt;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $PromptUser;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $PromptUserSpecified;

    /**
     * @type : string
     * @IsCollection : true
     */
    public $Dependencies;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $ValidValuesQueryBased;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $ValidValuesQueryBasedSpecified;

    /**
     * @type : ValidValue
     * @IsCollection : true
     */
    public $ValidValues;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $DefaultValuesQueryBased;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $DefaultValuesQueryBasedSpecified;

    /**
     * @type : string
     * @IsCollection : true
     */
    public $DefaultValues;


    /**
     * @type : ParameterStateEnum
     * @IsCollection : false
     * @IsEnum : true
     */
    public  $State;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $StateSpecified;

   /**
    * @type : string
    * @IsCollection : false
    */
    public $ErrorMessage;

    public function Initialize()
    {                   
        $this->TypeSpecified = isset($this->Type);
        $this->NullableSpecified = is_bool($this->Nullable);
        $this->AllowBlankSpecified = is_bool($this->AllowBlank);
        $this->MultiValueSpecified = is_bool($this->MultiValue);
        $this->QueryParameterSpecified = is_bool($this->QueryParameter);
        $this->PromptUserSpecified = is_bool($this->PromptUser);
        $this->ValidValuesQueryBasedSpecified = is_bool($this->ValidValuesQueryBased);
        $this->DefaultValuesQueryBasedSpecified = is_bool($this->DefaultValuesQueryBased);
        $this->StateSpecified = isset($this->State);
    }
    
    public function FromStdObject($stdObject)
    {
        throw new PReportException('Not Implemented');
    }
}
?>
