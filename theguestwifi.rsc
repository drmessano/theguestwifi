/system script
add dont-require-permissions=no name=theguestwifi owner=admin policy=read,write,test source=":local tgwpass ([/tool fetch http-method=post url=\"http://www.theguestwifi.com/\" http-data=\"api\" as-value output=user]->\"data\")\
  \n:if (\$tgwpass != \"\") do={:if ([:interface wireless security-profiles find name=\"theguestwifi\"] != \"\") do={:interface wireless security-profiles set [:interface wireless security-profiles find name=\"theguestwifi\"] wpa-pre-shared-key=\"\$tgwpass\" wpa2-pre-shared-key=\"\$tgwpass\"}\
  \n:if ([:caps-man configuration find ssid=\"TheGuestWifi\"] != \"\") do={:caps-man configuration set [:caps-man configuration find ssid=\"TheGuestWifi\" ] security.passphrase=\"\$tgwpass\"}}"
