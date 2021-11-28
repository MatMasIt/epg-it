sudo apt install php wget -y
wget https://iptv-org.github.io/epg/guides/it/guidatv.sky.it.epg.xml
if [ $? -ne 0 ]; then
    exit
fi
mkdir -p archivio/$(date '+%Y-%m-%d')
mv programmi/* archivio/$(date '+%Y-%m-%d')
mv guidatv.sky.it.epg.xml.1 guidatv.sky.it.epg.xml
php xmlParseProgrammeList.php
