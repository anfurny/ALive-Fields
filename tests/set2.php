<?php

date_default_timezone_set('America/Los_Angeles');
require_once( "UnitTests.php");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class MultiPermissionsTest extends AcResponseTest
{
    
     /**
     * Make sure a request with obselete token cannot be loaded
     * 
     * @expected ErrorException
     */
    public function testInvalidSession()
    {
        //make sure we can't read from a field that doesn't have read permission        
        $request = '{"AcFieldRequest":"loadfield","primaryInfo":[null,"2"],"requesting_page":"FAKE!","request_field":"1","action":"dynamic_load","source_field":"1"}';              
        ////DON'T CORRECT TOKEN
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_YES, AcField::SAVE_YES);

                
        try {
         echo $textbox->request_handler(json_decode($request, true) );
        }
        catch (Exception $e) {
            $this->assertTrue($e instanceOf ErrorException);
            $this->assertTrue($e->getMessage() === AcField::ERROR_INVALID_TOKEN);
        }
    }
    
    /**
     * Make sure a field without load permission cannot be loaded
     * 
     * @expected ErrorException
     */
    public function testDenyLoadOptions()
    {
        //make sure we can't read from a field that doesn't have read permission
        $request = '{"AcFieldRequest":"getlist","filters":{"all_users":["6"]},"distinct":false,"requesting_page":"","request_field":"4","requester":"6","requester_text":"Charlie","requester_key":"4"}';
        $request = json_decode($request, true);        
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_NO, AcField::SAVE_NO);

        $request['requesting_page'] = AcField::$uniqueSessionToken;//make token valid
        $request['request_field'] = $textbox->get_unique_id(); //make request match this control
                       
        try {
            $textbox->request_handler($request);
        }
        catch (Exception $e) {
            $this->assertTrue($e instanceOf ErrorException);        
            $this->assertTrue($e->getMessage() === AcField::ERROR_LOAD_DISALLOWED);
            return;
        }        
        $this->assertTrue(false);//shouldn't get here
    }
    
    
    /**
     * Make sure a field without write permission cannot save 
     *
     * @expected ErrorException
     */
   public function testDenyWrite()
   {
        $request ='{"AcFieldRequest":"savefield","fieldInfo":[[null,"New Value"]],"action":"save","requesting_page":"","request_field":""}';
        $request = json_decode($request, true);        
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_YES, AcField::SAVE_NO);

        $request['requesting_page'] = AcField::$uniqueSessionToken;//make token valid
        $request['request_field'] = $textbox->get_unique_id(); //make request match this control
             
        try {
            $textbox->request_handler($request);
            }
        catch (Exception $e) {
            $this->assertTrue($e instanceOf ErrorException);
            $this->assertTrue($e->getMessage() === AcField::ERROR_SAVE_DISALLOWED);
            return;
        }        
        $this->assertTrue(false);//shouldn't get here
   }         
   
    /**
     * Make sure a field with load permission can load normally.
     * 
     * @expected ErrorException
     */
    public function testAllowLoadOptions()
    {
        //make sure we can't read from a field that doesn't have read permission
        $request = '{"AcFieldRequest":"getlist","filters":{"all_users":["6"]},"distinct":false,"requesting_page":"","request_field":"4","requester":"6","requester_text":"Charlie","requester_key":"4"}';
        $request = json_decode($request, true);        
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_YES, AcField::SAVE_NO);
  
        $request['requesting_page'] = AcField::$uniqueSessionToken;//make token valid
        $request['request_field'] = $textbox->get_unique_id(); //make request match this control
        
        $result = $textbox->request_handler($request);
        $this->assertTrue(count($result) === 8);
    }  
 
    /**
     * Makes sure save can work
     *
     */
   public function testDisallowWriteUniqueValue()
   {
        $keyToSave = '4';
        $requestLoad = '{"AcFieldRequest":"loadfield","primaryInfo":[null,"' . $keyToSave . '"],"requesting_page":"","request_field":"","action":"dynamic_load","source_field":"1"}';
        $requestSave = '{"AcFieldRequest":"savefield","fieldInfo":[[null,"New Value"]],"action":"save","requesting_page":"","request_field":""}';
        
        $requestLoad = json_decode($requestLoad, true);
        $requestSave = json_decode($requestSave, true);
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_YES, AcField::SAVE_YES);
                
        $requestLoad['requesting_page'] = AcField::$uniqueSessionToken;//make token valid
        $requestLoad['request_field'] = $textbox->get_unique_id(); //make request match this control
        
        $requestSave['requesting_page'] = AcField::$uniqueSessionToken;//make token valid
        $requestSave['request_field'] = $textbox->get_unique_id(); //make request match this control        
        
        try {
            $result = $textbox->request_handler($requestLoad); //right now, saves require a preceding load.
            $result = $textbox->request_handler($requestSave);
        }
        catch (Exception $e) {
            $this->assertTrue($e instanceOf ErrorException);
            $this->assertTrue($e->getMessage() === "expectedError");
            return;
        }        
        $this->assertTrue(false);//shouldn't get here
   }
   
     /**
     * Makes sure save can work
     */
   /*
   public function testAllowWrite()
   {
        $key_to_save = '4';
        $request_load_options = '{"AcFieldRequest":"getlist","filters":{},"distinct":false,"requesting_page":"","request_field":"4","requester":"6","requester_text":"Charlie","requester_key":"4"}';
        $request_load = '{"AcFieldRequest":"loadfield","primaryInfo":[null,"' . $key_to_save . '"],"requesting_page":"","request_field":"","action":"dynamic_load","source_field":"1"}';
        $request_save = '{"AcFieldRequest":"savefield","fieldInfo":[[null,"1"]],"action":"save","requesting_page":"","request_field":""}';
        
        $request_load_options = json_decode($request_load_options, true);
        $request_load = json_decode($request_load, true);
        $request_save = json_decode($request_save, true);
        
        $textbox = new AcListSelect("TestField", "TestTable", "id", AcField::LOAD_YES, AcField::SAVE_YES);
        
        $request_load_options['requesting_page'] = AcField::$unique_session_token;//make token valid
        $request_load_options['request_field'] = $textbox->get_unique_id(); //make request match this control
        
        $request_load['requesting_page'] = AcField::$unique_session_token;//make token valid
        $request_load['request_field'] = $textbox->get_unique_id(); //make request match this control
        
        $request_save['requesting_page'] = AcField::$unique_session_token;//make token valid
        $request_save['request_field'] = $textbox->get_unique_id(); //make request match this control        
        
        $result = $textbox->request_handler($request_load); //right now, saves require a preceding load.
        $result = $textbox->request_handler($request_load_options); //right now, saves require a preceding load.
        $result = $textbox->request_handler($request_save);
        
        $found_val = $this->db_adapter->query_read("SELECT TestField FROM TestTable where ID='4'");
        
        $this->assertTrue($found_val[0]['TestField'] === "Abc");
   }*/
}
