<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Model;

use RuntimeException;

use Doctrine\Common\Collections\Collection;
use DoctrineModule\Form\Element\Proxy as ProxyDoctrine;

class Proxy extends ProxyDoctrine
{
    public function getArrayKeyProperty($value)
    {
        if (!($om = $this->getObjectManager())) {
            throw new RuntimeException('No object manager was set');
        }

        if (!($targetClass = $this->getTargetClass())) {
            throw new RuntimeException('No target class was set');
        }

        $metadata = $om->getClassMetadata($targetClass);
        $result =   array();
        if ($value instanceof Collection) {
            foreach ($value as $entity) {
                $id = array_shift($metadata->getIdentifierValues($entity));
                $get = 'get' . ucfirst($this->property);
                try {
                    $name = $entity->$get();
                } catch (Exception $ex) {
                    throw new Exception(get_class($entity) . " does't not have " . $get . "method");
                }
                $result[$id]    =   $name;
            }
        }
        
        return $result;
    }
}
