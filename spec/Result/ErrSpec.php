<?php

namespace spec\Prewk\Result;

use Exception;
use Prewk\Result\Err;
use PhpSpec\ObjectBehavior;
use Prewk\Result\Ok;
use Prewk\Result\ResultException;

class ErrSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith("error");
        $this->shouldHaveType(Err::class);
    }

    function it_isnt_ok()
    {
        $this->beConstructedWith("error");
        $this->isOk()->shouldBe(false);
    }

    function it_is_err()
    {
        $this->beConstructedWith("error");
        $this->isErr()->shouldBe(true);
    }

    function it_doesnt_map()
    {
        $this->beConstructedWith("error");
        $this->map(function() {})->shouldBe($this);
    }

    function it_mapErrs()
    {
        $this->beConstructedWith("foo");
        $result = $this->mapErr(function($err) {
            return $err . "bar";
        });

        $result->shouldHaveType(Err::class);
        $result->unwrapErr()->shouldBe("foobar");
    }

    function it_returns_an_iterator()
    {
        $this->beConstructedWith("error");
        $this->iter()->shouldBe([]);
    }

    function it_shouldnt_and()
    {
        $this->beConstructedWith("error");
        $this->and(new Err("ignored"))->shouldReturn($this);
    }

    function it_shouldnt_andThen()
    {
        $this->beConstructedWith("error");
        $this->andThen(function() {})->shouldReturn($this);
    }

    function it_should_or()
    {
        $fallback = new Ok("value");

        $this->beConstructedWith("error");
        $this->or($fallback)->shouldBe($fallback);
    }

    function it_should_orElse()
    {
        $otherValue = null;

        $this->beConstructedWith("error");
        $this->orElse(function($err) use (&$otherValue) {
            $otherValue = new Err($err . "rorre");
            return $otherValue;
        })->shouldBe($otherValue);
    }

    function it_throws_if_orElse_closure_return_type_mismatch()
    {
        $this->beConstructedWith("error");
        $this->shouldThrow(ResultException::class)->during("orElse", [function() {
            return "Not a result";
        }]);
    }

    function it_unwrapOrs()
    {
        $this->beConstructedWith("error");
        $this->unwrapOr("valid")->shouldBe("valid");
    }

    function it_unwrapOrElses()
    {
        $this->beConstructedWith("error");
        $this->unwrapOrElse(function($err) {
            return "non-" . $err;
        })->shouldBe("non-error");
    }

    function it_throws_on_unwrap()
    {
        $this->beConstructedWith("error");
        $this->shouldThrow(ResultException::class)->during("unwrap");
    }

    function it_throws_on_expect()
    {
        $msg = new Exception("error");
        $this->beConstructedWith("error");
        $this->shouldThrow($msg)->during("expect", [$msg]);
    }

    function it_unwrapErrs()
    {
        $this->beConstructedWith("error");
        $this->unwrapErr()->shouldBe("error");
    }
}