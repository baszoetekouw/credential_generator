function fill_credentials(cred)
{
	// check if credentials were generated
	if (cred.error==true)
	{
		alert("Sorry, an error occurred while retrieving your credentials. "
			+"Please notify support@surfconext.nl if this error persists");
		return;
	}

	// fill retrieved credentials into text fields
	$("#cred_passphrase").val(cred.passphrase);
	$("#cred_refcode"   ).val(cred.refcode);
}

function generate_cred()
{
	// fetch new credentials
	$.getJSON("credential.php", fill_credentials);
}

function on_ready()
{
	// add click handler to credentials button
	$("#cred_button").click(generate_cred);

	// make cfedentials inputs read-only
	$("#cred_passphrase").prop('readonly',true);
	$("#cred_refcode"   ).prop('readonly',true);
}

$(document).ready(on_ready);
