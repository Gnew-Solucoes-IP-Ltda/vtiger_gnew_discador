from db import MysqlConn
from settings import LEAD_STATUS


class Lead():

    def __init__(self, lead_no):
        try:
            mysql_conn = MysqlConn()
            mysql_conn.query(
                '''SELECT 
                    vtiger_leaddetails.leadid, 
                    vtiger_leaddetails.lead_no , 
                    vtiger_leaddetails.firstname, 
                    vtiger_leaddetails.lastname, 
                    vtiger_leadaddress.phone, 
                    vtiger_leadaddress.mobile 
                FROM vtiger_leadaddress 
                INNER JOIN vtiger_campaignleadrel ON vtiger_leadaddress.leadaddressid = vtiger_campaignleadrel.leadid 
                INNER JOIN vtiger_leaddetails ON vtiger_leaddetails.leadid = vtiger_leadaddress.leadaddressid 
                WHERE vtiger_leaddetails.lead_no = "{}"'''.format(lead_no)
            )
            self.leadid, self.lead_no, self.firstname, self.lastname, self.phone, self.mobile = mysql_conn.cursor.fetchone()    
            mysql_conn.disconnect()

        except:
            self.leadid = None
            self.lead_no  = None 
            self.firstname = None 
            self.lastname = None 
            self.phone = None 
            self.mobile = None
        
    def lead2dict(self):
        return {
            'leadid' : self.leadid,
            'lead_no' : self.lead_no,
            'firstname' : self.firstname,
            'lastname' : self.lastname,
            'phone' : self.phone,
            'self.mobile' : self.mobile
        }


class Campaign():

    def __init__(self, campaign_no):

        try:
            mysql_conn = MysqlConn()
            mysql_conn.query(
                '''SELECT 
                    campaignid,
                    campaign_no,
                    campaignname,
                    campaigntype,
                    campaignstatus,
                    product_id,
                    targetaudience,
                    closingdate 
                FROM vtiger_campaign
                WHERE campaign_no="{}"
                '''.format(campaign_no)
            )
            self.campaignid, self.campaign_no, self.campaignname, self.campaigntype, self.campaignstatus, self.product_id, self.targetaudience, self.closingdate = mysql_conn.cursor.fetchone()
                    
        except:
            self.campaignid = None
            self.campaign_no = None
            self.campaignname = None
            self.campaigntype = None
            self.campaignstatus = None
            self.product_id = None
            self.targetaudience = None
            self.closingdate = None
    
    def obter_leads(self):
        mysql_conn = MysqlConn()
        mysql_conn.query(
            '''SELECT 
                vtiger_leaddetails.lead_no 
            FROM vtiger_leaddetails 
            INNER JOIN vtiger_campaignleadrel ON vtiger_leaddetails.leadid =  vtiger_campaignleadrel.leadid
            WHERE vtiger_campaignleadrel.campaignid = "{}"'''.format(self.campaignid)
        )
        leads = [
            Lead(lead[0])
            for lead in mysql_conn.cursor.fetchall()
        ]
        mysql_conn.disconnect()
        return leads
    
    def obter_lead_livre(self):
        mysql_conn = MysqlConn()
        query = '''
            SELECT 
                vtiger_leaddetails.lead_no 
            FROM vtiger_leaddetails 
            INNER JOIN vtiger_campaignleadrel ON vtiger_leaddetails.leadid =  vtiger_campaignleadrel.leadid
            WHERE 
                vtiger_campaignleadrel.campaignid = "{}"
            AND
                vtiger_campaignleadrel.campaignrelstatusid = "{}"
        '''.format(self.campaignid, LEAD_STATUS['LIVRE'])
        mysql_conn.query(query)
        
        try:
            lead_no = mysql_conn.cursor.fetchone()[0]
        
        except:
            mysql_conn.disconnect()
            return None
            
        lead = Lead(lead_no)
        query = "UPDATE vtiger_campaignleadrel SET campaignrelstatusid = '{}' WHERE leadid = '{}'".format(
            LEAD_STATUS['TENTATIVA_DISCAGEM'],
            lead.leadid
        )
        mysql_conn = MysqlConn()
        mysql_conn.query(query)
        mysql_conn.commit()
        mysql_conn.disconnect()
        return lead