<!--Sabai Technology - Apache v2 licence
    copyright 2014 Sabai Technology -->
	<script type='text/javascript'>

		var hidden,hide,f,oldip='',limit=10,logon=false,info=null;

		function setLog(res){ 
			E('response').value = res; 
		}

		function saveEdit(){ 
			hideUi("Adjusting OpenVPN..."); 
			E("_act").value='save'; 
			que.drop( "php/ovpn.php", OVPNresp, $("#fe").serialize() );
		}

		function toggleEdit(){
		 $('#ovpn_controls').hide();
		 E('logButton').style.display='none';
		 E('edit').className='';
		 E('editButton').style.display='none';
<?php
  		if ($authpass = file('/etc/sabai/openvpn/auth-pass')) {
  				echo "uname =  '";
  				echo rtrim($authpass[0]);
  				echo "'\npass = '" . $authpass[1] . "'";
}
?> 
 	         typeof uname === 'undefined' || $('#VPNname').val(uname);
                 typeof pass === 'undefined'  || $('#VPNpassword').val(pass);		

		 // var conf=E('conf');
		 // var leng=(conf.value.match(/\n/g)||'').length;
		 // conf.style.height=(leng<15?'15':leng)+'em';
		}

		function toggleLog(){
		 if(logon=!logon){ 
		 	que.drop('php/ovpn.php', setLog, 'act=log'); 
		 }
		 E('logButton').value = (logon?'Hide':'Show') + " Log";
		 E('response').className = (logon?'tall':'hiddenChildMenu');
		 $('#editButton').toggle();
		}

		function load(){
		var ovpnfile='<?php $filename=exec('uci get openvpn.sabai.filename'); echo $filename; ?>';
		document.getElementById('ovpn_file').innerHTML = ovpnfile;
		E('ovpn_file').innerHTML = 'Current File: ' + ovpnfile;
-		 msg('Please supply a .conf/.ovpn complete configuration or a DD-WRT style .sh script.');
		}

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

		function OVPNresp(res){ 
			eval(res); 
			msg(res.msg); 
			showUi(); 
			if(res.reload){ 
				window.location.reload(); 
			}; 
			if(res.sabai){ 
				limit=10; getUpdate(); 
			} 
		}

		function OVPNsave(act){ 
			hideUi("Adjusting OpenVPN..."); 
			E("_act").value=act; 
			que.drop( "php/ovpn.php", OVPNresp, $("#fe").serialize() ); 
		}

		function init(){ 
			f = E('fe'); 
			hidden = E('hideme'); 
			hide = E('hiddentext'); 
			load(); 
	   getUpdate();
	   setInterval (getUpdate, 5000); 
	}

	</script>
<div class='pageTitle'>VPN: OpenVPN Client</div>
<div class='controlBox'><span class='controlBoxTitle'>OpenVPN Settings</span>
	<div class='controlBoxContent'>
<body onload='init();' id='topmost'>
<form id='newfile' method='post' action='php/ovpn.php' encType='multipart/form-data'>
						<input type='hidden' name='act' value='newfile'>

						<span id='ovpn_file'></span>
						<p>
						<span id='upload'>
						<input type='file' id='file' name='file'>
						<input type='button' value='Upload' onclick='submit()'></span>
						</p>
						<p>
						<span id='messages'>&nbsp;</span>
						</p>
					</form>
<form id='fe'>
							<span id='ovpn_controls'>
							<input type='hidden' id='_act' name='act' value=''>
							<input type='button' value='Start' onclick='OVPNsave("start");'>
							<input type='button' value='Stop' onclick='OVPNsave("stop");'>
							<input type='button' value='Clear' onclick='OVPNsave("clear");'></span>
							<input type='button' value='Show Log' id='logButton' onclick='toggleLog();'>
							<input type='button' value='Edit Config' id='editButton' onclick='toggleEdit();'>
						</div>
							
						<textarea id='response' class='hiddenChildMenu'></textarea>
						<div id='edit' class='hiddenChildMenu'>
						 <table>
						 	<tr><td>Name: </td><td><input type='text' name='VPNname' id='VPNname' placeholder='(optional)'></td></tr>
						 	<tr><td>Password:</td><td><input type='text' name='VPNpassword' id='VPNpassword' placeholder='(optional)'></td></tr>
						 </table>
						 
						 <br>
						 <textarea id='conf' class='tall' name='conf'>
						 	<?php readfile('/etc/sabai/openvpn/ovpn.current'); ?>
						 </textarea> <br>
						 <input type='button' value='Save' onclick='saveEdit();'>
						 <input type='button' value='Cancel' onclick='window.location.reload();'>
						</div>
                </tbody>
            </table>
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