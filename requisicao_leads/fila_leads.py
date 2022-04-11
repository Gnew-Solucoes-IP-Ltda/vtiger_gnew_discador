import json
from datetime import datetime
from redis import Redis


r = Redis()
chave_fila = 'fila_pedido_leads'
chave_usuario = 'gnew_discador_user_'


def obter_fila_leads():
    fila = json.loads(r.get(chave_fila)) if r.exists(chave_fila) else {}
    r.delete(chave_fila)
    return fila


class FilaLeads():

    def __init__(self):
        self.fila = obter_fila_leads()
    
    def associar_lead_usuario(self, lead, usuario):
        dados_lead = lead.lead2dict()

        if lead:
            dados_lead['data'] = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            r.set(
                f'{chave_usuario}{usuario}', 
                json.dumps(
                    dados_lead
                )
            )
            return True
        
        return False