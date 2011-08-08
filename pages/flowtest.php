<?php 
$pluginData = OpenVBX::$currentPlugin->getInfo();
require_once $pluginData['plugin_path'] . '/FlowTest.php';
$flowTest = new flowTest(OpenVBX::$currentPlugin);

$exception = false;

if(isset($_POST['callsid'])){
    $flowTest->setCallSid($_POST['callsid']);
}

try{
    //make an incomming request to the client, and redirect the call to the flow
    if(isset($_POST['testflow']) AND array_key_exists($_POST['flow'], $flowTest->getFlows())){
        $flowTest->callFlow($_POST['flow']);
    }
    
    //make an incomming request to the client, with a simple say script
    if(isset($_POST['testsay'])){
        $flowTest->callSay($_POST['say']);
    }
} catch (Exception $exception) {
    
}

?>

<div class="vbx-plugin">
<?php if($exception):?>
	<div class="notify">
		<p class="message">Could not call your OpenVBX Browser Phone - is it online?<a href class="close action"></a></p>
	</div>
<?php endif;?>
<?php if($flowTest->getCallSid()):?>
	<div class="notify">
		<p class="message">Connected to the OpenVBX Browser Phone. You can continue to test without hanging up.<a href class="close action"></a></p>
	</div>
<?php endif;?>
    <h2>Flow Test</h2>
    <p></p>
    <p>Select a flow to test using the OpenVBX Browser Phone:</p>
    <form action="" method="post">
        <?php echo form_dropdown('flow', $flowTest->getFlows()) ?>
        <button class="submit-button ui-state-focus" type="submit" name="testflow"><span>Test Flow</span></button>
        <input type="hidden" name="callsid" value="<?php echo $flowTest->getCallSid()?>">

    <h2>Say Test</h2>
    <p>Test the text-to-speech engine with this text:</p>
    
        <textarea name="say"><?php echo htmlentities($_POST['say'])?></textarea>
        <button class="submit-button ui-state-focus" type="submit" name="testsay"><span>Read Text</span></button>
        <input type="hidden" name="callsid" value="<?php echo $flowTest->getCallSid()?>">
    </form>
</div>