$.validator.methods.email = function( value, element ) {
	return this.optional( element ) || /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/.test( value );
};
$( document ).ready( function () {
	$( "#registration-form" ).validate( {
		rules: {
			email: {
				required: true,
				email: true
			},
			username:{
				required: true,
				minlength: 5,
				maxlength: 20
			},
			fname:{
				required: true,
				minlength: 2,
				maxlength: 20
			},
			lname:{
				required: true,
				minlength: 2,
				maxlength: 20
			},
			bday:{
				required: true,
				dateISO: true
			},
			country:{
				required: true
			},
			contactno:{
				required: true,
				number: true,
				minlength: 7
			},
			dcurrency: {
				required: true
			},
			dmethod: {
				required: true
			}
		},
		messages: {
			email: "Provide your email address!",
			username: {
				required: "Please provide your username!",
				minlength: "Make sure your Username is 5 to 20 characters long!",
				maxlength: "Make sure your Username is 5 to 20 characters long!",
			},
			fname: {
				required: "Enter your First Name!",
				minlength: "Make sure your First Name is 2 to 20 characters long!",
				maxlength: "Make sure your First Name is 2 to 20 characters long!",
			},
			lname: "Enter your Last Name!",
			bday: "Please enter a date in the format dd-mm-yyyy!",
			country: "Select your country!",
			contactno: {
				minlength: "Phone number must be at least 7 digits!",
				required: "Enter any of your Contact No.!",
				number: "Invalid Phone No.!"
			},
			dcurrency:{
				required: "Select deposit currency!"
			},
			dmethod:{
				required: "Select deposit method!"
			}
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.appendTo($(element).siblings(".validation-message").children(".validation-content").children(".form-control-feedback"));
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".required" ).addClass( "has-danger" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".required" ).addClass( "has-success" ).removeClass( "has-danger" );
		}
	} );
} );
