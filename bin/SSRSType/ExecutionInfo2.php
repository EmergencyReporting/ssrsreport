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
  * class ExecutionInfo2
  */
class ExecutionInfo2  extends SSRSBaseType implements ISSRSBaseType
{    
    /**
     * @type : bool
     * @IsCollection : false
     */
    public $HasSnapshot;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $NeedsProcessing;
    
    /**
     * @type : bool
     * @IsCollection : false
     */
    public $AllowQueryExecution;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $CredentialsRequired;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $ParametersRequired;

    /**
     * @type : DateTime
     * @IsCollection : false
     */
    public $ExpirationDateTime;

    /**
     * @type : DateTime
     * @IsCollection : false
     */
    public $ExecutionDateTime;

    /**
     * @type : int
     * @IsCollection : false
     */
    public $NumPages;

    /**
     * @type : ReportParameter
     * @IsCollection : true
     */
    public $Parameters;

    /**
     * @type : DataSourcePrompt
     * @IsCollection : true
     */
    public $DataSourcePrompts;

    /**
     * @type : bool
     * @IsCollection : false
     */
    public $HasDocumentMap;

    /**
     * @type : string
     * @IsCollection : false
     */
    public $ExecutionID;

    /**
     * @type : string
     * @IsCollection : false
     */
    public $ReportPath;

    /**
     * @type : string
     * @IsCollection : false
     */
    public $HistoryID;

    /**
     * @type : PageSettings
     * @IsCollection : false
     */
    public $ReportPageSettings;

    /**
     * @type : int
     * @IsCollection : false
     */
    public $AutoRefreshInterval;

    /**
     * @type : PageCountModeEnum
     * @IsCollection : false
     * @IsEnum : true
     */
     public  $PageCountMode;

    public function Initialize()
    {
    }
    
    public function FromStdObject($stdObject)
    {
        $objectVars = get_object_vars ($stdObject);        
        return parent::FromStdObject_Base('self', $objectVars["executionInfo"]);
    }
}
?>
