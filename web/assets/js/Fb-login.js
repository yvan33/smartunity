    function goLogIn(){
        window.location.href = "{{ path('_security_check') }}";
    }

    function onFbInit() {
        if (typeof(FB) != 'undefined' && FB != null ) {              
            FB.Event.subscribe('auth.statusChange', function(response) {
                if (response.session || response.authResponse) {
                    setTimeout(goLogIn, 500);
                } else {
                    window.location.href = "{{ path('_security_logout') }}";
                }
            });
        }
    }
