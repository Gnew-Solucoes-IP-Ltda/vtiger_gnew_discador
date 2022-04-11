#!/bin/bash

echo "Instalando bibliotecas Linux"
apt-get update
apt-get install redis php-redis python3-pip

echo "Instalando bibliotecas python"
pip3 install -r requirements.txt

echo "Copiando arquivos"
cp -a ../requisicao_leads /usr/src/

echo "Habilitando servico"
cp systemd/requisicao_leads.service  /etc/systemd/system/
chmod 755 /etc/systemd/system/requisicao_leads.service
systemctl enable requisicao_leads
systemctl daemon-reload
systemctl start requisicao_leads.service

