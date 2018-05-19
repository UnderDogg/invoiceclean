<div class="panel panel-default">

    @if (!isset($_ENV['POSTMARK_API_TOKEN']))
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('texts.email_settings') }}</h3>
          </div>
          <div class="panel-body form-padding-right">
            {!! Former::select('mail[driver]')->label('driver')->options(['smtp' => 'SMTP', 'mail' => 'Mail', 'sendmail' => 'Sendmail', 'mailgun' => 'Mailgun'])
                     ->value(HTMLUtils::getEnvForAccount('MAIL_DRIVER', 'smtp'))->setAttributes(['onchange' => 'mailDriverChange()']) !!}
            {!! Former::text('mail[from][name]')->label('from_name')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_FROM_NAME'))  !!}
            {!! Former::text('mail[from][address]')->label('from_address')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_FROM_ADDRESS'))  !!}
            {!! Former::text('mail[username]')->label('username')
                     ->value(HTMLUtils::getEnvForAccount('MAIL_USERNAME'))  !!}
            <div id="standardMailSetup">
              {!! Former::text('mail[host]')->label('host')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_HOST')) !!}
              {!! Former::text('mail[port]')->label('port')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_PORT', '587'))  !!}
              {!! Former::select('mail[encryption]')->label('encryption')
                      ->options(['tls' => 'TLS', 'ssl' => 'SSL', '' => trans('texts.none')])
                      ->value(HTMLUtils::getEnvForAccount('MAIL_ENCRYPTION', 'tls'))  !!}
              {!! Former::password('mail[password]')->label('password')
                      ->value(HTMLUtils::getEnvForAccount('MAIL_PASSWORD'))  !!}
            </div>
            <div id="mailgunMailSetup">
              {!! Former::text('mail[mailgun_domain]')->label('mailgun_domain')
                      ->value(isset($_ENV['MAILGUN_DOMAIN']) ? $_ENV['MAILGUN_DOMAIN'] : '') !!}
              {!! Former::text('mail[mailgun_secret]')->label('mailgun_private_key')
                      ->value(isset($_ENV['MAILGUN_SECRET']) ? $_ENV['MAILGUN_SECRET'] : '')  !!}
            </div>
              {!! Former::actions( Button::primary(trans('texts.send_test_email'))->small()->withAttributes(['onclick' => 'testMail()']), '&nbsp;&nbsp;<span id="mailTestResult"/>' ) !!}
          </div>
        </div>
    @endif

  <script type="text/javascript">

    var db_valid = false
    var mail_valid = false
    mailDriverChange();

    function testDatabase()
    {
      var data = $("form").serialize() + "&test=db";

      // Show Progress Text
      $('#dbTestResult').html('Working...').css('color', 'black');

      // Send / Test Information
      $.post( "{{ URL::to('/setup') }}", data, function( data ) {
        var color = 'red';
        if(data == 'Success'){
          color = 'green';
          db_valid = true;
        }
        $('#dbTestResult').html(data).css('color', color);
      });

      return db_valid;
    }

    function mailDriverChange() {
      if ($("select[name='mail[driver]']").val() == 'mailgun') {
        $("#standardMailSetup").hide();
        $("#standardMailSetup").children('select,input').prop('disabled',true);
        $("#mailgunMailSetup").show();
        $("#mailgunMailSetup").children('select,input').prop('disabled',false);

      } else {
        $("#standardMailSetup").show();
        $("#standardMailSetup").children('select,input').prop('disabled',false);

        $("#mailgunMailSetup").hide();
        $("#mailgunMailSetup").children('select,input').prop('disabled',true);

      }
    }

    function testMail()
    {
      var data = $("form").serialize() + "&test=mail";

      // Show Progress Text
      $('#mailTestResult').html('Working...').css('color', 'black');

      // Send / Test Information
      $.post( "{{ URL::to('/setup') }}", data, function( data ) {
        var color = 'red';
        if(data == 'Sent'){
          color = 'green';
          mail_valid = true;
        }
        $('#mailTestResult').html(data).css('color', color);
      });

      return mail_valid;
    }

    // Prevent the Enter Button from working
    $("form").bind("keypress", function (e) {
      if (e.keyCode == 13) {
        return false;
      }
    });

  </script>
