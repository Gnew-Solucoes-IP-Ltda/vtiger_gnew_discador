#! /usr/src/requisicao_leads/venv/bin/python3

from time import sleep
from vtiger import Campaign, obter_leads_status, tabular_lead
from fila_leads import FilaLeads


campanhas = {}
fila_leads = FilaLeads()
fila_leads.atualizar_lead_status(obter_leads_status())

while True:
    fila_leads.atualizar_fila()
    
    for usuario in fila_leads.fila:
        campanha_usuario = fila_leads.fila[usuario]['campanha']

        if not campanha_usuario in campanhas:
            campanhas[campanha_usuario] = Campaign(campanha_usuario)

        lead = campanhas[campanha_usuario].obter_lead_livre()
        fila_leads.associar_lead_usuario(lead, usuario)
    
    fila_tabulacao = fila_leads.obter_dados_tabulacao()

    for tabulacao in fila_tabulacao:
        tabular_lead(
            tabulacao['leadid'],
            tabulacao['leadstatus']
        )
        fila_leads.remover_fila_tabulacao(
            tabulacao['usuario']
        )

    sleep(1)