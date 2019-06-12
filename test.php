 <?php
public function build_contact_us_email($b_include_debug_info = false, $rt) {
    $db = \Shared\DB::get_connection('CT');
    $to_email = $db-&gt;query('SELECT * FROM dbRecipients WHERE userType = "'. $rt. '"');
     $id_array = array();
    foreach ($this-&gt;topic_array as &amp;$topic) {
      $id_array[] = $topic['Value'];
      $topic['Processed'] = true;
    }
    $error_code = '';
    if (!empty($this-&gt;topic_array)) {
      $faq_model = new FAQ_Section_Model($this-&gt;topic_array[0]['Value']);
      $error_code = $faq_model-&gt;get_error_code($id_array);
    }

    $email_message = $this-&gt;build_customer_email_infomation($cu_id);
    $email_message .= $this-&gt;build_order_email_information();
    $email_message .= $this-&gt;build_email_message_details();

    if ($b_include_debug_info) {
      $email_message = $this-&gt;add_debug_info($email_message);
    }
  
    $email_message_text = $this-&gt;get_email_message_text($email_message);
    $this-&gt;is_site_feedback = false;
    $site_feedback_topic_ids = $this-&gt;dao-&gt;get_site_feedback_topic_ids();
    if (!empty($this-&gt;topic_array[0]['Name'])) {
      $this-&gt;is_site_feedback = in_array($this-&gt;topic_array[0]['Value'],  $site_feedback_topic_ids);
    }
  
    if ($rt == 1) {
      $subject .= 'Subject1';
    }
    elseif($rt = 2)
      $subject .= 'Subject2';
    elseif(17===$rt){
      $subject .= 'Subject18';
    }
    else {
       $subject .= 'UNDEFINED'; 
    }

    $current_language = $db-&gt;query('SELECT lang FROM dbLangs WHERE isDefault=1 AND code="de"');


    $subject .= ' ' . $error_code;


    if (!empty($this-&gt;queue_id) &amp;&amp; !empty($this-&gt;routing_id)) {
      $subject .= ' [*' . $this-&gt;queue_id . '*_' . $this-&gt;routing_id . ']';
    }
    $current_language = !empty($_COOKIE['wflang']) ? $_COOKIE['wflang'] : CNSESLANGUAGE;

    $email_data = [
        'subject' =&gt; $subject,
        'email_html' =&gt; $email_message,
        'email_text' =&gt; $email_message_text,
        'to_address' =&gt; $to_email,
        'cu_id' =&gt; $cu_id,
        'show_order' =&gt; $show_order,
        $current_language
    ];
        
    return $email_data;
  }
?>
