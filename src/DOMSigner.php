<?php

declare(strict_types=1);

namespace PhpCfdi\XmlCancelacion;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use DOMDocument;
use DOMElement;
use RuntimeException;

class DOMSigner
{
    /** @var DOMDocument */
    private $document;

    /** @var string */
    private $digestSource = '';

    /** @var string */
    private $digestValue = '';

    /** @var string */
    private $signedInfoSource = '';

    /** @var string */
    private $signedInfoValue = '';

    public function __construct(DOMDocument $document)
    {
        $this->document = $document;
    }

    public function getDigestSource(): string
    {
        return $this->digestSource;
    }

    public function getDigestValue(): string
    {
        return $this->digestValue;
    }

    public function getSignedInfoSource(): string
    {
        return $this->signedInfoSource;
    }

    public function getSignedInfoValue(): string
    {
        return $this->signedInfoValue;
    }

    public function sign(Credentials $signObjects): void
    {
        // Setup digestSource & digestValue
        // C14N: no exclusive, no comments (if exclusive will drop not used namespaces)
        $this->digestSource = $this->document->C14N(false, false);
        $this->digestValue = base64_encode(sha1($this->digestSource, true));

        $document = $this->document;

        /** @var DOMElement $signature */
        $signature = $document->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'Signature');
        $document->documentElement->appendChild($signature);

        // append and realocate signedInfo to the node in document
        // SIGNEDINFO
        $signedInfo = $signature->appendChild(
            $document->importNode($this->createSignedInfoElement(), true)
        );

        // need to append signature to document and signed info **before** C14N
        // otherwise the signedinfo will not contain namespaces
        // C14N: no exclusive, no comments (if exclusive will drop not used namespaces)
        $this->signedInfoSource = $signedInfo->C14N(false, false);
        $privateKey = new PemPrivateKey('file://' . $signObjects->privateKey());
        $privateKey->open($signObjects->passPhrase());
        $this->signedInfoValue = base64_encode($privateKey->sign($this->signedInfoSource, OPENSSL_ALGO_SHA1));
        $privateKey->close();

        // SIGNATUREVALUE
        $signature->appendChild(
            $document->createElementNS('http://www.w3.org/2000/09/xmldsig#', 'SignatureValue', $this->signedInfoValue)
        );

        // KEYINFO
        $signature->appendChild(
            $document->importNode($this->createKeyInfo($signObjects->certificate()), true)
        );
    }

    protected function createSignedInfoElement(): DOMElement
    {
        $template = '<SignedInfo>
              <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
              <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
              <Reference URI="">
                <Transforms>
                  <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                </Transforms>
                <DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                <DigestValue>' . $this->getDigestValue() . '</DigestValue>
              </Reference>
            </SignedInfo>';

        $docInfo = new DOMDocument();
        $docInfo->preserveWhiteSpace = false;
        $docInfo->formatOutput = false;
        $docInfo->loadXML($template);
        $docinfoNode = $docInfo->documentElement;

        return $docinfoNode;
    }

    protected function createKeyInfo(string $certificateFile): DOMElement
    {
        $document = $this->document;
        $certificate = new Certificado($certificateFile);

        $keyInfo = $document->createElement('KeyInfo');
        $x509Data = $document->createElement('X509Data');
        $x509IssuerSerial = $document->createElement('X509IssuerSerial');
        $x509IssuerName = $document->createElement('X509IssuerName', $certificate->getCertificateName());
        $x509SerialNumber = $document->createElement('X509SerialNumber', $certificate->getSerialObject()->asAscii());

        $x509IssuerSerial->appendChild($x509IssuerName);
        $x509IssuerSerial->appendChild($x509SerialNumber);
        $x509Data->appendChild($x509IssuerSerial);

        $certificateContents = implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $certificate->getPemContents())));
        $x509Certificate = $document->createElement('X509Certificate', $certificateContents);
        $x509Data->appendChild($x509Certificate);

        $keyInfo->appendChild($x509Data);

        $keyInfo->appendChild($this->createKeyValue($certificateFile));
        return $keyInfo;
    }

    protected function createKeyValue(string $certificateFile): DOMElement
    {
        $certificate = new Certificado($certificateFile);
        return $this->createKeyValueFromCertificado($certificate);
    }

    protected function createKeyValueFromCertificado(Certificado $certificate): DOMElement
    {
        $document = $this->document;
        $keyValue = $document->createElement('KeyValue');
        $pubKey = openssl_get_publickey($certificate->getPemContents());
        if (! is_resource($pubKey)) {
            throw new RuntimeException('Cannot read public key from certificate');
        }
        $pubKeyData = openssl_pkey_get_details($pubKey);
        if (OPENSSL_KEYTYPE_RSA === $pubKeyData['type']) {
            $rsaKeyValue = $keyValue->appendChild($document->createElement('RSAKeyValue'));
            $rsaKeyValue->appendChild($document->createElement('Modulus', base64_encode($pubKeyData['rsa']['n'])));
            $rsaKeyValue->appendChild($document->createElement('Exponent', base64_encode($pubKeyData['rsa']['e'])));
        }
        openssl_free_key($pubKey);

        return $keyValue;
    }
}
