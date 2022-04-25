# GNEW Discador Vtiger

O módulo Gnew Discador Vtiger foi desenvolvido para enfileirar os Leads cadastrados nas campanhas e disponibiliza-los para discagem automatizada através do PABX IP GNEW.

Estou considerando que o CRM vtiger já está instalado e funcionando.

## Ambiente utilizado nos testes
- Ubuntu Server 18.04.11
- Apache 2.4.29
- PHP 7.2.24
- Python 3.6.9
- Mysql Server 5.7.37
- Vtiger 7.4.0


## Instalação
Para instalação do módulo GNew Discador, primeiramente clone o repositório no servidor onde está implementado o vtiger e execute o script install.sh
```
# git clone https://github.com/Gnew-Solucoes-IP-Ltda/vtiger_gnew_discador.git
# cd vtiger_gnew_discador/Deploy/
# ./install.sh
```

O segundo passo é acessar o menu Configurações - Configurações CRM - Gestão de módulos - Módulos e importar o GnewDiscador.zip