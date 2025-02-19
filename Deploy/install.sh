#!/bin/bash

set -e

echo "Instalando bibliotecas Linux"
apt-get update
apt-get install -y redis php-redis python3-pip python3-venv

echo "Criando ambiente virtual"
python3 -m venv /usr/src/requisicao_leads/venv

echo "Ativando ambiente virtual e instalando bibliotecas Python"
source /usr/src/requisicao_leads/venv/bin/activate
pip install -r requirements.txt
deactivate

echo "Copiando arquivos"
if [ -d "../requisicao_leads" ]; then
    cp -a ../requisicao_leads /usr/src/
else
    echo "Diretório ../requisicao_leads não encontrado!"
    exit 1
fi

echo "Habilitando serviço"
if [ -f "systemd/requisicao_leads.service" ]; then
    cp systemd/requisicao_leads.service /etc/systemd/system/
    chmod 755 /etc/systemd/system/requisicao_leads.service
    systemctl enable requisicao_leads
    systemctl daemon-reload
    if ! systemctl is-active --quiet requisicao_leads.service; then
        systemctl start requisicao_leads.service
    fi
else
    echo "Arquivo systemd/requisicao_leads.service não encontrado!"
    exit 1
fi

echo "Instalação concluída com sucesso!"


