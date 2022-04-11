#! /usr/bin/python3

from time import sleep
from vtiger import Campaign
from fila_leads import FilaLeads


campanhas = {}
fila_leads = FilaLeads()

while True:
    for usuario in fila_leads.fila:
        campanha_usuario = fila_leads.fila[usuario]['campanha']

        if not campanha_usuario in campanhas:
            campanhas[campanha_usuario] = Campaign(campanha_usuario)

        lead = campanhas[campanha_usuario].obter_lead_livre()
        fila_leads.associar_lead_usuario(lead, usuario)

    sleep(1)