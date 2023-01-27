insert into itbp_clms.ci_email_templates (id, name, slug, subject, body, last_update)
values  (1, 'Email Verification', 'email-verification', 'Activate Your Account', '<p></p>
  
  <p>Hi  <b>{FULLNAME}</b>,<br><br></p><p>Welcome to Track & Trace ! To verify your email, please click the link below:<br></p><p> {VERIFICATION_LINK}</p><p> Username is <b>{USERNAME}</b> and Password is <b>{PASSWORD}</b><br><br>
 
  </p><div><b>Regards,</b></div><div><b>Team Track & Trace</b></div>
  
  <p></p>', '2021-09-16 01:25:02'),
        (2, 'Forget Password', 'forget-password', 'Recover your password', '<p>

</p><p>Hi  <b>{FULLNAME}</b>,<br><br></p><p>Welcome to LightAdmin!<br></p><p>We have received a request to reset your password. If you did not initiate this request, you can simply ignore this message and no action will be taken.</p><p><br>To reset your password, please click the link below:<br> {RESET_LINK}</p>

<p></p>', '2019-11-26 17:44:41'),
        (3, 'General Notification', '', 'aaaaa', '<p>asdfasdfasdfasd </p>', '2019-08-26 02:42:47'),
        (4, 'RFQ sent for Verification', 'rfq-verification', 'Verify RFQ', '<p>

</p><p>Dear Sir/ Ma''am,</p><p>Welcome to Track & Trace ! Verify the Generated RFQ:<br></p><p>The request has been raised from <b>{SHIPPER}</b> by 

<b>{CREATEDBY}</b>  at 

<b>{TIME}</b> for the destination <b>{CITY}</b>. The order number for this RFQ is 

<b>{ORDERNO}</b>. </p><p>Kindly verify the details and push to GO Comet.</p><p></p><div><b>Regards,</b></div><div><b>Team Track & Trace</b></div>

<p></p>', '2021-10-04 04:25:15');