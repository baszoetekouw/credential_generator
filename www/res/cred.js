function fill_credentials(cred)
{
	// enable submit button
	$("#cred_button").prop('disabled',false);
	$("#text1").prop('hidden',false);
	$("#text2").prop('hidden',true);

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
	// disable submit
	$("#cred_button").prop('disabled',true);
	$("#text1").prop('hidden',true);
	$("#text2").prop('hidden',false);

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

	// show correct button text
	$("#text1").prop('hidden',false);
	$("#text2").prop('hidden',true);
}

$(document).ready(on_ready);
