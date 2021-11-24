mv programmi archivio/$(date '+%Y-%m-%d')
mkdir programmi
wget https://iptv-org.github.io/epg/guides/it/guidatv.sky.it.epg.xml
if [ $? -ne 0 ]; then
    exit
fi
mv guidatv.sky.it.epg.xml.1 guidatv.sky.it.epg.xml
php xmlParseProgrammeList.php