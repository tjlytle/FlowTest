<?php 
$ci = &get_instance();
$ci->load->helper('form');

//get flow array for select
$flows = array();
foreach(OpenVBX::getFlows() as $flow){
    $flows[$flow->values['id']] = $flow->values['name'];
}

//make an incomming request to the client, and redirect the call to the flow
if(isset($_POST['flow']) AND array_key_exists($_POST['flow'], $flows)){
    //use the url helper to get the site url
    $this->load->helper('url');
    $response = new Response();
    //generate url to the selected flow
    $response->addRedirect(site_url('twiml/applet/voice/' . $_POST['flow'] .'/start'));
    //TODO: would be nice to do this without using the twimlet
    $url = "http://twimlets.com/echo?Twiml=" . $response->asURL(true);

    //start a call to the client
    $client = new TwilioRestClient($this->twilio_sid,$this->twilio_token);
    $response = $client->request(
    	"Accounts/".$this->twilio_sid."/Calls",
        "POST",
         array("Caller" => 'client:' . OpenVBX::getCurrentUser()->id,
               "To" => 	'client:' . OpenVBX::getCurrentUser()->id,
               "Url" => $url
         )
    );
} 
?>

<div class="vbx-plugin">
<?php if(isset($response) AND $response->IsError):?>
	<div class="notify">
		<p class="message">Could not call your OpenVBX Browser Phone - is it online?<a href class="close action"></a></p>
	</div>
<?php endif;?>
<p>Select a flow to test using the OpenVBX Browser Phone:</p>
<form action="" method="post">
    <?php echo form_dropdown('flow', $flows) ?>
    <button class="submit-button ui-state-focus" type="submit"><span>Test Flow</span></button>
</form>
</div>