# CHANGELOG

## Acerca de SemVer

Usamos [Versionado Semántico 2.0.0](SEMVER.md) por lo que puedes usar esta librería sin temor a romper tu aplicación.

## Listado de cambios

Pueden aparecer cambios no liberados que se integran a la rama principal, pero no ameritan una nueva liberación de versión,
aunque sí su incorporación en la rama principal de trabajo. Generalmente, se tratan de cambios en el desarrollo.

### Mantenimiento 2025-09-27

- Se corrige una prueba donde se estaba escribiendo el archivo esperado antes de su comparación.
- Se actualizan las herramientas `phpcs` y `phpcbf` a la versión 4.0.0.
- Se elimina la regla `Generic.Functions.CallTimePassByReference`.
- Se moderniza la integración con SonarQube-Cloud.
- Se actualiza `sonarqube-scan-action` a la versión 6.

### Versión 2.0.5 2025-06-19

Se corrige un error al momento de generar una solicitud con múltiples folios.
Anteriormente, solo se generaba un nodo `<Folios>` con múltiples `<Folio>` hijos.
Lo correcto según el estándar del SAT es generar múltiples nodos `<Folios>` con solo un `<Folio>` hijo.
Esto ha pasado desapercibido debido a que comúnmente solo se envía un folio por solicitud.
Gracias a `@daniel-monroy` por notar este problema y ayudarme a corregirlo.

### Versión 2.0.4 2025-06-01

- Se elimina la compatibilidad con versiones menores a PHP 8.1.
- Se actualiza PHPUnit a la rama 10.

### Versión 2.0.3 2025-06-01

- Se hacen las modificaciones para la compatibilidad con PHP 8.4.
- Se actualiza el año de la licencia.
- Se actualizan los flujos de trabajo de GitHub:
  - Se agrega PHP 8.4 a la matriz de prebas en el trabajo `tests`.
  - Los trabajos se ejecutan en PHP 8.4, excepto `php-cs-fixer`.
  - Se cambia el trabajo `infection` del flujo `coverage` a `build`.
  - Se cambia el flujo `coverage` a `sonarcloud`.
  - Se usa la nueva acción `SonarSource/sonarqube-scan-action@v5` en lugar de `sonarsource/sonarcloud-github-action@master`.
- Se remueve la herramienta *Psalm*, gracias.
- Se actualizan las herramientas de desarrollo.

### Mantenimiento 2024-09-09

- En los flujos de trabajo de GitHub:
  - Se actualizan las acciones a versión 4.
  - Se usa la varible `matrix.php-version` en lugar de `matrix.php-versions`.

### Mantenimiento 2024-01-22

- Se actualiza el año de la licencia.
- Se corrige el archivo de configuración de `php-cs-fixer`.
- Se corrige el código para solventar los problemas de `php-cs-fixer`, `psalm` y SonarCloud.
  No produjo cambios que requieran liberar una nueva versión.
- Se corrige el ancla del proyecto en el archivo `CONTRIBUTING.md`.
- Se corrige la insignia de construcción del proyecto en el archivo `README.md`.
- Se configura GitHub para ignorar la detección de lenguaje en la ruta `tests/_files/`.
- Se actualizan los flujos de trabajo de GitHub:
  - Se agrega PHP 8.3 a la matriz de prebas en el trabajo `tests`.
  - Los trabajos se ejecutan en PHP 8.3.
  - Se permite ejecutar los trabajos manualmente.
- Se actualizan las herramientas de desarrollo.

### Versión 2.0.2 2022-12-15

Se corrige el archivo XML generado para cancelaciones.
Anteriormente, cuando el atributo `FolioSustitucion` estaba vacío se incluía vacío, ahora se omite.

Este cambio va de acuerdo a la documentación del Anexo 20:

> *Atributo condicional que representa al UUID que sustituye al folio fiscal cancelado.*
> *Es requerido cuando la clave del motivo de cancelación es 01.*

Gracias `@juliazo` por reportar este problema: <https://github.com/phpcfdi/xml-cancelacion/issues/30>.

#### Mantenimiento 2022-12-15

- Se actualizan las herramientas de desarrollo.
- Se actualiza el estándar de código al más reciente de PhpCfdi.
- Se actualizan los flujos de trabajo de GitHub:
  - Se agrega PHP 8.2 a la matriz de prebas en el trabajo `tests`.
  - Actualizar a PHP 8.2 (excepto el trabajo `php-cs-fixer`).
  - Se actualizan las acciones estándar de GitHub de la versión 2 a la versión 3.
  - Se quita la instalación de la herramienta `composer` donde no es necesaria.
  - Se cambia la directiva deprecada `::set-output` por `$GITHUB_OUTPUT`.

#### Mantenimiento 2022-02-23

- Se actualiza el año en el archivo de licencia. Feliz 2022.
- Se corrige el grupo de mantenedores de phpCfdi.
- Se actualizan las dependencias de desarrollo.
- Se corrige el archivo de configuración de Psalm porque el atributo `totallyTyped` está deprecado.
- Se deja de utilizar Scrutinizer CI. Gracias Scrutinizer CI.
- El flujo de integración continua se cambia para separar los procesos que dependen de la cobertura de código.
- Se agregan los modificadores `abstract`o `final` a las clases de pruebas.

### Versión 2.0.1 2022-01-10

Se corrige el XML namespace de cancelación de retenciones. Quedan de la siguiente forma:

- CFDI Regulares: `http://cancelacfd.sat.gob.mx`.
- CFDI Retenciones: `http://www.sat.gob.mx/esquemas/retencionpago/1`.

### Versión 2.0.0 2022-01-08

- Se actualiza al nuevo esquema de datos de cancelación del SAT, ahora no se pide un arreglo de UUID,
  se pide un objeto `CancelDocuments`. Se crean diferentes objetos de valor relacionados con los nuevos campos.
- Se cambia el namespace `PhpCfdi\XmlCancelacion\Definitions` a `PhpCfdi\XmlCancelacion\Models`.
- Actualización de licencia, feliz 2022.

### UNRELEASED 2021-11-15

- Se actualizan las dependencias de las librerías de desarrollo.
- Se hacen correcciones de problemas menores a las pruebas encontradas por la nueva versión de PHPStan.

### UNRELEASED 2021-10-25

- Se corrige el archivo de configuración de `phpstan` en `.gitattributes`.
- Se remueve la anotación del tipo de variable para `DOMElement::documentElement`, `psalm` ya lo detecta correctamente.
- Se actualizan las versiones de dependencias en `.phive/phars.xml`.

### Versión 1.1.2 2021-09-03

- La versión menor de PHP es 7.3.
- Se actualiza PHPUnit a 9.5.
- Se migra de Travis-CI a GitHub Workflows. Gracias Travis-CI.
- Se instalan las herramientas de desarrollo usando `phive` en lugar de `composer`.
- Se agregan revisiones de `psalm` e `infection`.
- Se cambia la rama principal a `main`.
- Se agrega un `trait` interno para obtener el elemento principal de un documento XML.
- Se usan constantes privadas descriptivas en lugar de números mágicos.

### UNRELEASED 2021-01-12

- La documentación del proyecto cambia a español.
- Se agrega PHP 8.0 a la integración continua.
- Se modifican los comandos de construcción para usar composer versión 2.
- Actualización del año en la licencia, feliz 2021 desde PhpCfdi.

### UNRELEASED 2020-11-12

- Fix Travis-CI build: `phpstan: ^0.12.54` detects issues on unit tests control flow.

### Version 1.1.1 2020-08-28

- Refactor `CreateKeyInfoElementTraitTest` explaining what it is for.
    - Add a class to use `CreateKeyInfoElementTrait` changing `createKeyInfoElement` method visibility.
    - Make 3 different tests for 3 different cases instead of all in one test.
    - Fixes recently (false positive) issue detected by `phpstan/phpstan:^0.12.40`.
- Change dependency `robrichards/xmlseclibs` version from `^3.0.8` to `3.1.0`.

### Version 1.1.0 2020-01-23

- Update license year, happy 2020!
- Include cancellation document for document *"CFDI de retención e información de pagos"*.
- Add `DocumentType` enumerator with keys `cfdi` and `retention` to specify the correct namespace of the request.
- Add `XmlCancellationHelper::signRetentionCancellation` and `XmlCancellationHelper::signRetentionCancellationUuids`
  that create cancellation request for retentions.
- Refactor `XmlCancelacionHelper` and delegate the creation of the `Cancellation` object to a specific
  protected method.
- Other capsules uses also `DocumentType` to set the main namespace but as hardcoded.
- Development:
    - Move from `phpstan/phpstan-shim` to `phpstan/phpstan`.
    - Upgrade to `phpstan/phpstan: ^0.12`
- Testing:
    - Improve `CancellationTest` to check that `DocumentType` is used correctly.
    - Create a testing class `XmlCancelacionHelperSpy` to spy on `XmlCancelacionHelper`.
    - Refactor tests on `XmlCancelacionHelperTest` to test against the spy class.  

### Version 1.0.1 2019-10-02

- Fix documentation to point out version 1.0 instead of 0.5.
- Fix documentation PHP examples.

### Version 1.0.0 2019-09-28

- This version is a major change, it is not compatible with previous versions
  Read [UPGRADE-1.0](https://github.com/phpcfdi/xml-cancelacion/blob/main/docs/UPGRADE-1.0.md)
- New signed documents:
    - Cancellation: Request SAT for cancellation of one or many CFDI.
    - ObtainRelated: Request SAT for related documents of a CFDI.
    - CancellationAnswer: Answer SAT about a cancellation request.
- New project concepts:
    - Capsule: DTO that contains source data, also implements `CapsuleInterface`
    - Signer: Manipulation object implementing `SignerInterface`, takes a DOMDocument and append signature data
- New general process:
    - A `CapsuleInterface` is exported as a `DOMDocument`
    - Using a `SignerInterface` the `DOMDocument` is signed and signature is appended.
    - Signature is exported from `DOMDocument::saveXml()`
- New helper `XmlCancelacionHelper`:
    - If no `SignerInterface` is provided then the default is used
- Credentials can be created from `PhpCfdi\Credentials\Credential`
- Exceptions are specific to this library:
    - `XmlCancelacionException`
        - `XmlCancelacionLogicException` extends `LogicException`
            - `DocumentWithoutRootElement`
            - `HelperDoesNotHaveCredentials`
        - `XmlCancelacionRuntimeException` extends `RuntimeException`
            - `CannotLoadCertificateAndPrivateKey`
            - `CapsuleRfcDoesnotBelongToCertificateRfc`
            - `CertificateIsNotCSD`


### Version 0.4.2 2019-09-05

- Include a helper object `XmlCancelacionHelper` that simplify working with this library,
  see [README](https://github.com/phpcfdi/xml-cancelacion/blob/main/README.md) for usage.
- Other minimal changes on documentation.


### Version 0.4.1 2019-08-13

- Fix X509IssuerName to use certificate issuer as [RFC 4514](https://www.ietf.org/rfc/rfc4514.txt)
  according to [XML Signature Syntax and Processing Version 1.1](https://www.w3.org/TR/xmldsig-core1/)
  [4.5.4 The X509Data Element](https://www.w3.org/TR/xmldsig-core1/#sec-X509Data) where it states that:
  *The deprecated X509IssuerSerial element, which contains an X.509 issuer distinguished name/serial number pair.*
  *The distinguished name SHOULD be represented as a string that complies with section 3 of RFC4514, to be generated*
  *according to the [Distinguished Name Encoding Rules](https://www.w3.org/TR/xmldsig-core1/#dname-encrules) section*.
  Notice that in the encoding rules mention additional rules that __MAY__ be implemented. We are not. 


### Version 0.4.0 2019-08-13

- Drop dependence from `eclipxe/cfdiutils` to `phpcfdi/credentials`
- `PhpCfdi\XmlCancelacion\Credentials` changed from DTO to encapsulate certificate & private key logic:
    - uses internally `PhpCfdi\Credentials\Credential`
    - offers methods to extract any data or execute any action from certificate or private key
- Minor improvements in documentation


### Version 0.3.0 2019-06-27

- Fix issue when calling `DOMDocument::createElement`/`DOMDocument::createElementNS` and content has an empersand `&`:
    - `KeyInfo/X509Data/X509IssuerSerial/X509IssuerName`
    - `Folios/UUID`
    - Other uses on `createElement[NS]` are not important since they cannot have any ampersand.
- Depends on `eclipxe/cfdiutils` version 2.10.4 to use `Xml::createElement` & `Xml::createElementNS`.
- Move certificate extracting info from `DOMSigner::createKeyInfo` to `DOMSigner::sign`
- Change signature of `DOMSigner::createKeyInfo(Certificado)` to
  `DOMSigner::createKeyInfoElement(string $issuerName, string $serialNumber, string $pemContents)`, we can test it now.
- Extract `DOMSigner::createKeyValueFromCertificado` to `DOMSigner::createKeyValueElement` to allow testing.
- Remove protected method `DOMSigner::createKeyValueFromCertificado` in favor of `DOMSigner::createKeyValueElement`.


### Version 0.2.1 2019-05-13

- Release with new tag since v0.2.0 did not include this changes


### Version 0.2.0 2019-05-13

- Code quality improvements thanks to phpstan and infection (mutation testing framework)
- Remove `Capsule::append` method, `Capsule` is now immutable.
- Throw `LogicException` when `DOMSigner` uses a document that does not have root element
- `Capsule` now implements `Countable`


### Version 0.1.0 2019-04-09

- First public version
