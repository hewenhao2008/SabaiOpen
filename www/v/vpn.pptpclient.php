<!DOCTYPE html>
<html>
<!--Sabai Technology - Apache v2 licence
    copyright 2014 Sabai Technology -->

<form id="fe">
<div class='pageTitle'>VPN: PPTP Client</div>
<div class='controlBox'><span class='controlBoxTitle'>PPTP Settings</span>
    <div class='controlBoxContent'>
<body onload='init();' id='topmost'>
        <input type='hidden' id='act' name='act'>
        <div class='section'>
            <table class="fields">
                <tbody>
                    <tr>
                        <td class="title indent1 shortWidth">Server</td>
                        <td class="content">
                            <input name="server" id="server" class='longinput' type="text">
                        </td>
                    </tr>
                    <tr>
                        <td class="title indent1 shortWidth">Username</td>
                        <td class="content">
                            <input name="user" id="user" class='longinput' type="text">
                        </td>
                    </tr>
                    <tr>
                        <td class="title indent1 shortWidth">Password</td>
                        <td class="content">
                            <input name="pass" id="pass" class='longinput' autocomplete="off" onfocus='peekaboo("pass")' onblur='peekaboo("pass")' type="password">
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type='button' class='firstButton' value='Start' onclick='PPTPcall("start")'>
            <input type='button' value='Stop' onclick='PPTPcall("stop")'>
            <input type='button' value='Save' onclick='PPTPcall("save")'>
            <input type='button' value='Clear' onclick='PPTPcall("clear")'> <span id='messages'>&nbsp;</span>
            <br>
        </div>
        </form>
    <div id='hideme'>
        <div class='centercolumncontainer'>
            <div class='middlecontainer'>
                <div id='hiddentext'>Please wait...</div>
                <br>
            </div>
        </div>
    </div>
    <p>
        <div id='footer'>Copyright © 2014 Sabai Technology, LLC</div>
    </p>
</body>

<script type='text/javascript'>
    var hidden, hide, f,oldip='',limit=10,info=null,ini=false;

    pptp = {<?php
        $user=trim(exec("uci get sabai.vpn.username"));
        $pass=trim(exec("uci get sabai.vpn.password"));
        $server=trim(exec("uci get sabai.vpn.server"));
        if( $user!="" ) echo "\n\tuser: '". $user ."',\n\tpass: '". $pass ."',\n\tserver: '". $server ."'\n";
        else echo " user: '', pass: '', server: '' ";
    ?>}

function setUpdate(res){
            if(info) oldip = info.vpn.ip; 
            eval(res); 
            if(oldip!='' && info.vpn.ip==oldip){ 
                limit--; 
            }; 
            if(limit<0) return; 

            for(i in info.vpn){ 
                E('vpn'+i).innerHTML = info.vpn[i]; 
            }
}

function getUpdate(ipref){ 
            que.drop('php/info.php',setUpdate,ipref?'do=ip':null); 
       $.get('php/get_remote_ip.php', function( data ) {
         donde = $.parseJSON(data.substring(6));
         console.log(donde);
         for(i in donde) E('loc'+i).innerHTML = donde[i];
       });
}

function PPTPresp(res){ 
  eval(res); 
  msg(res.msg); 
  showUi(); 
  if(res.sabai){ 
    limit=10; 
    getUpdate(); 
  } 
}

function PPTPcall(act){ 
  hideUi("Adjusting PPTP settings..."); 
  E("act").value=act;
// Pass the form values to the php file 
$.post('php/pptp.php', $("#fe").serialize(), function(res){
  // Detect if values have been passed back   
    if(res!=""){
      PPTPresp(res);
    }
      showUi();
});
        if(act =='clear'){ 
        setTimeout("window.location.reload()",5000);
            }; 
 
// Important stops the page refreshing
return false;

}; 



function init(){ 
    f = E('fe'); 
    hidden = E('hideme'); 
    hide = E('hiddentext'); 
    for(var i in pptp){ 
        E(i).value = pptp[i]; 
    }; 
                <?php if (file_exists('/etc/sabai/stat/ip') && file_get_contents("/etc/sabai/stat/ip") != '') {
       echo "donde = $.parseJSON('" . strstr(file_get_contents("/etc/sabai/stat/ip"), "{") . "');\n";
       echo "for(i in donde){E('loc'+i).innerHTML = donde[i];}"; } ?>
       getUpdate();
       setInterval (getUpdate, 5000)
}
  
</script>

