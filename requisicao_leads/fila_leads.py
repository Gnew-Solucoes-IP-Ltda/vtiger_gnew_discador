import json
from datetime import datetime
from redis import Redis


r = Redis()
chave_fila = 'fila_pedido_leads'
chave_usuario = 'gnew_discador_user_'
chave_fila_tabulacao = 'fila_tabulacao_leads'
tabulacao_usuario = 'gnew_discador_tabulacao_'


def obter_fila_leads(chave=chave_fila):
    fila = json.loads(r.get(chave)) if r.exists(chave) else {}
    r.delete(chave)
    return fila


class FilaLeads():

    def __init__(self):
        self.fila = obter_fila_leads()
        self.pendente_tabulacao = json.loads(
            r.get(chave_fila_tabulacao)
        ) if r.exists(chave_fila_tabulacao) else []
    
    def atualizar_fila(self):
        self.fila = obter_fila_leads()
    
    def associar_lead_usuario(self, lead, usuario):
        if lead:
            dados_lead = lead.lead2dict()
            dados_lead['data'] = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            r.set(
                f'{chave_usuario}{usuario}', 
                json.dumps(
                    dados_lead
                )
            )

            if usuario not in self.pendente_tabulacao:
                self.pendente_tabulacao.append(
                    usuario
                )
                r.set(
                    f'{chave_fila_tabulacao}', 
                    json.dumps(
                        self.pendente_tabulacao
                    )
                )

            return True
        
        else:
            r.delete(
                f'{chave_usuario}{usuario}'
            )

        return False
    
    def obter_dados_tabulacao(self):
        dados = []

        for usuario in self.pendente_tabulacao:
            if r.exists(f'{tabulacao_usuario}{usuario}'):
                tabulacao = json.loads(r.get(f'{tabulacao_usuario}{usuario}'))
                dados.append(
                    {
                        'usuario' : usuario,
                        'leadid' : tabulacao['leadid'],
                        'leadstatus' : tabulacao['leadstatus']
                    }
                )
        
        return dados
    
    def remover_fila_tabulacao(self, usuario):
        if usuario in self.pendente_tabulacao:
            self.pendente_tabulacao.remove(
                usuario
            )
            r.set(
                f'{chave_fila_tabulacao}', 
                json.dumps(
                    self.pendente_tabulacao
                )
            )
            r.delete(
                f'{chave_usuario}{usuario}'
            )
            r.delete(
                f'{tabulacao_usuario}{usuario}'
            )
            return True
        
        return False
