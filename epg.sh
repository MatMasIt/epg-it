sudo apt install php wget -y
wget https://iptv-org.github.io/epg/guides/it/guidatv.sky.it.xml
if [ $? -ne 0 ]; then
    exit
fi
mkdir -p archivio/$(date '+%Y-%m-%d')
mv programmi/* archivio/$(date '+%Y-%m-%d')
mv guidatv.sky.it.xml.1 guidatv.sky.it.xml
php xmlParseProgrammeList.php
