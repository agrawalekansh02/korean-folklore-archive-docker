<?php

setcookie('kfl','',time() - 3600,'/');
header('Location: http://uwc.wp.ucla.edu/Shibboleth.sso/Logout?entityId='.HOST.'&return=https://shb.ais.ucla.edu/shibboleth-idp/Logout');

