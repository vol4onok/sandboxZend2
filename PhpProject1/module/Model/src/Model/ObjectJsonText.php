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

use Model\Proxy;
use Zend\Form\Element\Hidden as Hidden;
use Doctrine\Common\Collections\Collection;
class ObjectJsonText extends Hidden
{
    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @return Proxy
     */
    public function getProxy()
    {
        if (null === $this->proxy) {
            $this->proxy = new Proxy();
        }
        return $this->proxy;
    }

    /**
     * @param  array|\Traversable $options
     * @return ObjectSelect
     */
    public function setOptions($options)
    {
        $this->getProxy()->setOptions($options);
        return parent::setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function setValue($value)
    {   
        if ($value instanceof Collection) {
            return parent::setValue($this->getJson($this->getProxy()->getArrayKeyProperty($value)));
        } else if (Is_array($value)) {
            return parent::setValue($this->getJson(array_flip($value)));
        }
    }

    public function getJson($data = array())
    {
       return json_encode($data, JSON_FORCE_OBJECT);
    }
}
