<?php

namespace App\Http\Controllers;

use App\Services\SoapUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SoapController extends Controller
{
    public function handle(Request $request): Response
    {
        if ($request->isMethod('get')) {
            return $this->serveWsdl();
        }

        $wsdlPath = $this->getOrCreateWsdl();

        ob_start();
        try {
            $server = new \SoapServer($wsdlPath, [
                'encoding'   => 'UTF-8',
                'cache_wsdl' => WSDL_CACHE_NONE,
            ]);
            $server->setObject(new SoapUserService());
            $server->handle($request->getContent());
        } catch (\Throwable $e) {
            ob_end_clean();
            $msg = htmlspecialchars($e->getMessage(), ENT_XML1 | ENT_QUOTES, 'UTF-8');
            return response(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">'
                . '<SOAP-ENV:Body><SOAP-ENV:Fault>'
                . '<faultcode>Server</faultcode>'
                . "<faultstring>{$msg}</faultstring>"
                . '</SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>',
                500,
                ['Content-Type' => 'text/xml; charset=UTF-8']
            );
        }

        return response(ob_get_clean(), 200, ['Content-Type' => 'text/xml; charset=UTF-8']);
    }

    private function getOrCreateWsdl(): string
    {
        $path = storage_path('app/users.wsdl');

        if (!file_exists($path)) {
            file_put_contents($path, $this->buildWsdl());
        }

        return $path;
    }

    private function serveWsdl(): Response
    {
        // Regenerate on each GET so the URL stays accurate
        $content = $this->buildWsdl();
        file_put_contents(storage_path('app/users.wsdl'), $content);

        return response($content, 200, ['Content-Type' => 'text/xml; charset=UTF-8']);
    }

    private function buildWsdl(): string
    {
        $serviceUrl = url('/soap');
        $ns         = 'http://lequotidien.local/soap/users';

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<definitions xmlns="http://schemas.xmlsoap.org/wsdl/"
             xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
             xmlns:tns="{$ns}"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema"
             targetNamespace="{$ns}"
             name="UsersService">

  <types>
    <xsd:schema targetNamespace="{$ns}">

      <xsd:complexType name="ReponseSimple">
        <xsd:all>
          <xsd:element name="succes"  type="xsd:boolean"/>
          <xsd:element name="message" type="xsd:string"/>
        </xsd:all>
      </xsd:complexType>

      <xsd:complexType name="ReponseData">
        <xsd:all>
          <xsd:element name="succes"  type="xsd:boolean"/>
          <xsd:element name="message" type="xsd:string"/>
          <xsd:element name="data"    type="xsd:string"/>
        </xsd:all>
      </xsd:complexType>

    </xsd:schema>
  </types>

  <message name="listerUtilisateursRequest">
    <part name="token" type="xsd:string"/>
  </message>
  <message name="listerUtilisateursResponse">
    <part name="return" type="tns:ReponseData"/>
  </message>

  <message name="ajouterUtilisateurRequest">
    <part name="token"    type="xsd:string"/>
    <part name="nom"      type="xsd:string"/>
    <part name="email"    type="xsd:string"/>
    <part name="password" type="xsd:string"/>
    <part name="role"     type="xsd:string"/>
  </message>
  <message name="ajouterUtilisateurResponse">
    <part name="return" type="tns:ReponseSimple"/>
  </message>

  <message name="modifierUtilisateurRequest">
    <part name="token" type="xsd:string"/>
    <part name="id"    type="xsd:int"/>
    <part name="nom"   type="xsd:string"/>
    <part name="email" type="xsd:string"/>
    <part name="role"  type="xsd:string"/>
  </message>
  <message name="modifierUtilisateurResponse">
    <part name="return" type="tns:ReponseSimple"/>
  </message>

  <message name="supprimerUtilisateurRequest">
    <part name="token" type="xsd:string"/>
    <part name="id"    type="xsd:int"/>
  </message>
  <message name="supprimerUtilisateurResponse">
    <part name="return" type="tns:ReponseSimple"/>
  </message>

  <message name="authentifierUtilisateurRequest">
    <part name="email"    type="xsd:string"/>
    <part name="password" type="xsd:string"/>
  </message>
  <message name="authentifierUtilisateurResponse">
    <part name="return" type="tns:ReponseData"/>
  </message>

  <portType name="UsersPortType">
    <operation name="listerUtilisateurs">
      <input  message="tns:listerUtilisateursRequest"/>
      <output message="tns:listerUtilisateursResponse"/>
    </operation>
    <operation name="ajouterUtilisateur">
      <input  message="tns:ajouterUtilisateurRequest"/>
      <output message="tns:ajouterUtilisateurResponse"/>
    </operation>
    <operation name="modifierUtilisateur">
      <input  message="tns:modifierUtilisateurRequest"/>
      <output message="tns:modifierUtilisateurResponse"/>
    </operation>
    <operation name="supprimerUtilisateur">
      <input  message="tns:supprimerUtilisateurRequest"/>
      <output message="tns:supprimerUtilisateurResponse"/>
    </operation>
    <operation name="authentifierUtilisateur">
      <input  message="tns:authentifierUtilisateurRequest"/>
      <output message="tns:authentifierUtilisateurResponse"/>
    </operation>
  </portType>

  <binding name="UsersBinding" type="tns:UsersPortType">
    <soap:binding style="rpc" transport="http://schemas.xmlsoap.org/soap/http"/>

    <operation name="listerUtilisateurs">
      <soap:operation soapAction="listerUtilisateurs"/>
      <input><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>
    <operation name="ajouterUtilisateur">
      <soap:operation soapAction="ajouterUtilisateur"/>
      <input><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>
    <operation name="modifierUtilisateur">
      <soap:operation soapAction="modifierUtilisateur"/>
      <input><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>
    <operation name="supprimerUtilisateur">
      <soap:operation soapAction="supprimerUtilisateur"/>
      <input><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>
    <operation name="authentifierUtilisateur">
      <soap:operation soapAction="authentifierUtilisateur"/>
      <input><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></input>
      <output><soap:body use="encoded" namespace="{$ns}" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/></output>
    </operation>
  </binding>

  <service name="UsersService">
    <port name="UsersPort" binding="tns:UsersBinding">
      <soap:address location="{$serviceUrl}"/>
    </port>
  </service>

</definitions>
XML;
    }
}
